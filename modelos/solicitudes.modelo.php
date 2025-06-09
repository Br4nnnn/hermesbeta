<?php

require_once "conexion.php";

class ModeloSolicitudes
{

    static public function mdlMostrarEquiposDisponible($fechaInicio, $fechaFin)
    {

        $stmt = Conexion::conectar()->prepare("SELECT e.*,
                                                    c.nombre AS categoria_nombre,
                                                    u.nombre AS ubicacion_nombre,
                                                    us.nombre AS cuentadante_nombre
                                                FROM 
                                                    equipos e
                                                JOIN 
                                                    categorias c ON e.categoria_id = c.categoria_id
                                                JOIN 
                                                    ubicaciones u ON e.ubicacion_id = u.ubicacion_id
                                                JOIN 
                                                    usuarios us ON e.cuentadante_id = us.id_usuario                                                
                                                WHERE e.id_estado = 1 OR (
                                                    e.id_estado = 3 AND NOT EXISTS (
                                                        SELECT 1
                                                        FROM prestamos p
                                                        JOIN detalle_prestamo dp ON p.id_prestamo = dp.id_prestamo
                                                        WHERE dp.equipo_id = e.equipo_id
                                                        AND p.tipo_prestamo = 'Reservado'
                                                        AND (
                                                            (p.fecha_inicio BETWEEN :fechaInicio AND :fechaFin) OR
                                                            (p.fecha_fin BETWEEN :fechaInicio AND :fechaFin) OR
                                                            (:fechaInicio BETWEEN p.fecha_inicio AND p.fecha_fin) OR
                                                            (:fechaFin BETWEEN p.fecha_inicio AND p.fecha_fin)
                                                        )
                                                    )
                                                )"
        );

        $stmt->bindParam(":fechaInicio", $fechaInicio, PDO::PARAM_STR);
        $stmt->bindParam(":fechaFin", $fechaFin, PDO::PARAM_STR);

        // error_log("Fecha Inicio: $fechaInicio, Fecha Fin: $fechaFin", 0); 

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return "vacio";
        }

        $stmt->close();
        $stmt = null;
    } // fin del metodo mdlMostrarEquiposDisponible

    static public function mdlGuardarSolicitud($tabla, $datos){

        $conexion = Conexion::conectar();

        try{
            $conexion->beginTransaction();
            $sql = "INSERT INTO $tabla (usuario_id, tipo_prestamo, fecha_inicio, fecha_fin, estado_prestamo, motivo) VALUES (:usuario_id, :tipo_prestamo, :fechaInicio, :fechaFin, :estado_prestamo, :motivo)";

            // error log de la consulta SQL con los datos enviados para debuggear
            error_log("INSERT INTO $tabla (usuario_id, tipo_prestamo, fecha_inicio, fecha_fin, estado_prestamo, motivo) VALUES ({$datos['usuario_id']}, {$datos['tipo_prestamo']}, {$datos['fecha_inicio']}, {$datos['fecha_fin']}, {$datos['estado_prestamo']}, {$datos['motivo']})");

            // error_log("SQL: $sql", 0);
            error_log("Datos: " . print_r($datos, true), 0); 
            

            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(":usuario_id", $datos["usuario_id"], PDO::PARAM_INT);
            $stmt->bindParam(":tipo_prestamo", $datos["tipo_prestamo"], PDO::PARAM_STR);
            $stmt->bindParam(":fechaInicio", $datos["fecha_inicio"], PDO::PARAM_STR);
            $stmt->bindParam(":fechaFin", $datos["fecha_fin"], PDO::PARAM_STR);
            $stmt->bindParam(":estado_prestamo", $datos["estado_prestamo"], PDO::PARAM_STR);
            $stmt->bindParam(":motivo", $datos["motivo"], PDO::PARAM_STR);
            $stmt->execute();

            $idPrestamo = $conexion->lastInsertId();

            foreach ($datos["equipos"] as $equipo) {
                $stmt2 = $conexion->prepare("INSERT INTO detalle_prestamo (id_prestamo, equipo_id, estado) VALUES (:id_prestamo, :equipo_id, 'asignado')", );
                $stmt2->bindParam(":id_prestamo", $idPrestamo, PDO::PARAM_INT);
                $stmt2->bindParam(":equipo_id", $equipo, PDO::PARAM_INT);
                $stmt2->execute();
            }

            $conexion->commit();
            return "ok";

        }catch(PDOException $e){
            $conexion->rollBack();
            return "error";
        }       
        
    } //metodo mdlGuardarSolicitud

   
    public static function mdlContarEquiposPorCategoria() {
        $stmt = Conexion::conectar()->prepare("SELECT categoria_id, COUNT(*) as cantidad
            FROM equipos
            WHERE id_estado = 1
            GROUP BY categoria_id
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // [categoria_id => cantidad]
    }

    static public function mdlMostrarSolicitudes($item, $valor)
    {

        $stmt = Conexion::conectar()->prepare("SELECT p.*  FROM prestamos p WHERE p.$item = :$item");
        $stmt->bindParam(":" . $item, $valor, PDO::PARAM_INT);
        $stmt->execute();
        //VERIFICAMOS EL TAMAÑO DE LA RESPUESTA
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return "vacio";
        }

        $stmt->close();
        $stmt = null;
    }

    static public function mdlMostrarPrestamo($item, $valor)
    {

        if($item != null){
            $stmt = Conexion::conectar()->prepare("SELECT p.*, u.id_usuario, ur.id_rol, r.nombre_rol FROM prestamos p  
            JOIN usuarios u ON p.usuario_id = u.id_usuario 
            JOIN usuario_rol ur ON u.id_usuario = ur.id_usuario
            JOIN roles r on ur.id_rol = r.id_rol
            WHERE p.$item = :$item");
            $stmt->bindParam(":" . $item, $valor, PDO::PARAM_INT);
            $stmt->execute();
    
            //verificamos el tamaño de la respuesta
            if($stmt->rowCount() == 1){
                return $stmt->fetch(PDO::FETCH_ASSOC);  
            }else{
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }else{
            $stmt = Conexion::conectar()->prepare("SELECT  p.*, CONCAT(u.nombre, ' ', u.apellido) AS solicitante, u.numero_documento FROM prestamos p JOIN usuarios u ON p.usuario_id = u.id_usuario");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }


        $stmt->close();
        $stmt = null;

    }

    static public function mdlMostrarPrestamoDetalle($item, $valor)
    {

        $stmt = Conexion::conectar()->prepare("SELECT dp.*, e.*, u.nombre as ubicacion, c.nombre as categoria
                                                FROM detalle_prestamo dp 
                                                JOIN equipos e ON dp.equipo_id = e.equipo_id
                                                JOIN ubicaciones u ON e.ubicacion_id = u.ubicacion_id
                                                JOIN categorias c ON e.categoria_id = c.categoria_id
                                                WHERE dp.$item = :$item");

        $stmt->bindParam(":" . $item, $valor, PDO::PARAM_INT);
        $stmt->execute();

        //verificamos el tamaño de la respuesta
        if($stmt->rowCount() == 1){
            return $stmt->fetchAll(PDO::FETCH_ASSOC);            
        }else{
            return $stmt->fetchAll(PDO::FETCH_ASSOC);            
        }


        $stmt->close();
        $stmt = null;
    }

    static public function mdlMostrarHistorial($tabla, $item, $valor)
    {
        if ($item != null) {
            // continue;
            $stmt = Conexion::conectar()->prepare("SELECT *  FROM $tabla WHERE $item = :$item");
            $stmt->bindParam(":" . $item, $valor, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } else {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
            $stmt->execute();
            return $stmt->fetchAll();
        }
        $stmt->close();
        $stmt = null;
    }

    



}//ModeloSolicitudes
