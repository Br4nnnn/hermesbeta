<?php
require_once "conexion.php";



class Modelosalida
{
    static public function mdlMostrarsalida($tabla, $item, $valor)
    {
        if ($item != null) {
            // Consulta para un registro específico con JOIN, devolviendo cada equipo por separado
            // Se añade la condición para que solo muestre equipos con detalle_prestamo.id_estado = 2 (Prestado)
            $stmt = Conexion::conectar()->prepare(
                "SELECT p.*, u.numero_documento, u.nombre, u.apellido, u.telefono, 
                    f.codigo as ficha_codigo, 
                    e.equipo_id, e.numero_serie, e.descripcion, e.etiqueta, 
                    c.nombre AS categoria_nombre, 
                    dp.id_estado AS estado_del_equipo_en_prestamo 
                FROM $tabla p
                JOIN usuarios u ON p.usuario_id = u.id_usuario
                LEFT JOIN aprendices_ficha af ON u.id_usuario = af.id_usuario
                LEFT JOIN fichas f ON af.id_ficha = f.id_ficha
                LEFT JOIN detalle_prestamo dp ON p.id_prestamo = dp.id_prestamo
                LEFT JOIN equipos e ON dp.equipo_id = e.equipo_id
                LEFT JOIN categorias c ON e.categoria_id = c.categoria_id
                WHERE p.$item = :$item
                AND p.estado_prestamo IN ('Prestado', 'Autorizado')
                AND dp.id_estado = 2" // Condición agregada aquí para filtrar equipos en estado 'Prestado'
            );

            $stmt->bindParam(":" . $item, $valor, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } else {
            // Consulta para todos los registros con JOIN (sin cambios aquí)
            $stmt = Conexion::conectar()->prepare(
                "SELECT p.id_prestamo, u.numero_documento, u.nombre, u.apellido, u.telefono,
                    f.codigo as ficha_codigo,
                    p.fecha_inicio, p.fecha_fin, p.tipo_prestamo,
                    p.estado_prestamo
            
                FROM $tabla p
                JOIN usuarios u ON p.usuario_id = u.id_usuario
                LEFT JOIN aprendices_ficha af ON u.id_usuario = af.id_usuario
                LEFT JOIN fichas f ON af.id_ficha = f.id_ficha
                WHERE p.estado_prestamo IN ('Autorizado', 'Trámite')
                ORDER BY p.fecha_inicio DESC"
            );

            $stmt->execute();
            return $stmt->fetchAll();
        }

        
        $stmt = null;
    }
    
    

    static public function mdlPrestarPrestamo($idPrestamo) {
        $stmt = Conexion::conectar()->prepare(
            "UPDATE prestamos SET estado_prestamo = 'Prestado' WHERE id_prestamo = :id"
        );
        $stmt->bindParam(":id", $idPrestamo, PDO::PARAM_INT);

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                return ["status" => "ok", "mensaje" => "Préstamo actualizado"];
            } else {
                return ["status" => "sin_cambios", "mensaje" => "No se encontró el préstamo o ya estaba prestado"];
            }
        } else {
            return ["status" => "error", "mensaje" => $stmt->errorInfo()];
        }
    }

    static public function mdlAceptarSalida($tabla, $datos){
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla (id_prestamo, id_usuario) VALUES (:id_prestamo,:id_usuario)");

        $stmt->bindParam(":id_prestamo", $datos["id_prestamo"], PDO::PARAM_INT);
        $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        } 
        
        $stmt -> close();
        $stmt = null;    
    }

} //ModeloSalidas
