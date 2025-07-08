<?php

require_once "conexion.php";

class ModeloAutorizaciones
{

    // modelo para mostrar autorizaciones
    static public function mdlMostrarAutorizaciones($tabla, $item, $valor)
    {
        if ($item != null) {
            $stmt = Conexion::conectar()->prepare("
                SELECT 
                    p.id_prestamo,
                    CASE 
                        WHEN EXISTS (
                            SELECT 1 
                            FROM autorizaciones a 
                            WHERE a.id_prestamo = p.id_prestamo 
                            AND a.id_rol = (SELECT id_rol FROM roles WHERE nombre_rol = 'Coordinacion')
                            AND a.motivo_rechazo IS NULL
                        ) THEN 'Firmado' 
                        ELSE 'Pendiente' 
                    END AS firma_coordinacion,
                    (SELECT a.id_usuario 
                     FROM autorizaciones a 
                     WHERE a.id_prestamo = p.id_prestamo 
                     AND a.id_rol = (SELECT id_rol FROM roles WHERE nombre_rol = 'Coordinacion')
                     LIMIT 1) AS id_usuario_coordinacion,
                    (SELECT CONCAT(u.nombre, ' ', u.apellido) 
                     FROM autorizaciones a 
                     JOIN usuarios u ON a.id_usuario = u.id_usuario
                     WHERE a.id_prestamo = p.id_prestamo 
                     AND a.id_rol = (SELECT id_rol FROM roles WHERE nombre_rol = 'Coordinacion')
                     LIMIT 1) AS nombre_usuario_coordinacion,
                    CASE 
                        WHEN EXISTS (
                            SELECT 1 
                            FROM autorizaciones a 
                            WHERE a.id_prestamo = p.id_prestamo 
                            AND a.id_rol = (SELECT id_rol FROM roles WHERE nombre_rol = 'Lider TIC')
                            AND a.motivo_rechazo IS NULL
                        ) THEN 'Firmado' 
                        ELSE 'Pendiente' 
                    END AS firma_lider_tic,
                    (SELECT a.id_usuario 
                     FROM autorizaciones a 
                     WHERE a.id_prestamo = p.id_prestamo 
                     AND a.id_rol = (SELECT id_rol FROM roles WHERE nombre_rol = 'Lider TIC')
                     LIMIT 1) AS id_usuario_lider_tic,
                    (SELECT CONCAT(u.nombre, ' ', u.apellido) 
                     FROM autorizaciones a 
                     JOIN usuarios u ON a.id_usuario = u.id_usuario
                     WHERE a.id_prestamo = p.id_prestamo 
                     AND a.id_rol = (SELECT id_rol FROM roles WHERE nombre_rol = 'Lider TIC')
                     LIMIT 1) AS nombre_usuario_lider_tic,
                    CASE 
                        WHEN EXISTS (
                            SELECT 1 
                            FROM autorizaciones a 
                            WHERE a.id_prestamo = p.id_prestamo 
                            AND a.id_rol = (SELECT id_rol FROM roles WHERE nombre_rol = 'Almacén')
                            AND a.motivo_rechazo IS NULL
                        ) THEN 'Firmado' 
                        ELSE 'Pendiente' 
                    END AS firma_almacen,
                    (SELECT a.id_usuario 
                     FROM autorizaciones a 
                     WHERE a.id_prestamo = p.id_prestamo 
                     AND a.id_rol = (SELECT id_rol FROM roles WHERE nombre_rol = 'Almacén')
                     LIMIT 1) AS id_usuario_almacen,
                    (SELECT CONCAT(u.nombre, ' ', u.apellido) 
                     FROM autorizaciones a 
                     JOIN usuarios u ON a.id_usuario = u.id_usuario
                     WHERE a.id_prestamo = p.id_prestamo 
                     AND a.id_rol = (SELECT id_rol FROM roles WHERE nombre_rol = 'Almacén')
                     LIMIT 1) AS nombre_usuario_almacen,
                    (SELECT a.motivo_rechazo 
                     FROM autorizaciones a 
                     WHERE a.id_prestamo = p.id_prestamo 
                     AND a.motivo_rechazo IS NOT NULL
                     LIMIT 1) AS motivo_rechazo,
                    (SELECT a.id_rol 
                     FROM autorizaciones a 
                     WHERE a.id_prestamo = p.id_prestamo 
                     AND a.motivo_rechazo IS NOT NULL
                     LIMIT 1) AS rol_que_rechazo,
                    (SELECT CONCAT(u.nombre, ' ', u.apellido) 
                     FROM autorizaciones a 
                     JOIN usuarios u ON a.id_usuario = u.id_usuario
                     WHERE a.id_prestamo = p.id_prestamo 
                     AND a.motivo_rechazo IS NOT NULL
                     LIMIT 1) AS usuario_que_rechazo
                FROM prestamos p
                WHERE p.id_prestamo = :id_prestamo
            ");

            $stmt->bindParam(":id_prestamo", $valor, PDO::PARAM_INT);

            $stmt->execute();
            return $stmt->fetch();

            $stmt->close();
            $stmt = null;
        }
    }

    static public function mdlAutorizarPrestamo($id_prestamo, $id_rol, $id_usuario)
    {
        $stmt = Conexion::conectar()->prepare("INSERT INTO autorizaciones (id_prestamo, id_rol, id_usuario) VALUES (:id_prestamo, :id_rol, :id_usuario)");

        $stmt->bindParam(":id_prestamo", $id_prestamo, PDO::PARAM_INT);
        $stmt->bindParam(":id_rol", $id_rol, PDO::PARAM_INT);
        $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }

        $stmt->close();
        $stmt = null;
    }

    static public function mdlDesautorizarPrestamo($id_prestamo, $id_rol, $id_usuario)
    {
        $stmt = Conexion::conectar()->prepare("DELETE FROM autorizaciones WHERE id_prestamo = :id_prestamo AND id_rol = :id_rol AND id_usuario = :id_usuario");

        $stmt->bindParam(":id_prestamo", $id_prestamo, PDO::PARAM_INT);
        $stmt->bindParam(":id_rol", $id_rol, PDO::PARAM_INT);
        $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }

        $stmt->close();
        $stmt = null;
    }


    static public function mdlRechazarPrestamo($tabla, $datos)
    {
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla (id_prestamo, id_rol, id_usuario, motivo_rechazo) VALUES (:id_prestamo, :id_rol, :id_usuario, :motivo_rechazo)");

        //mostramos error log de la consulta para debuggear

        $stmt->bindParam(":id_prestamo", $datos["id_prestamo"], PDO::PARAM_INT);
        $stmt->bindParam(":id_rol", $datos["id_rol"], PDO::PARAM_INT);
        $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
        $stmt->bindParam(":motivo_rechazo", $datos["motivo_rechazo"], PDO::PARAM_STR);

        if ($stmt->execute()) {
            return "ok";
        } else {
            error_log(print_r($stmt->errorInfo(), true));
            return "error";
        }

        $stmt->close();
        $stmt = null;
    }

    static public function mdlListarPrestamosAutorizados($filtros = [])
    {
        $sql = "SELECT p.id_prestamo, u.nombre AS solicitante, p.fecha_inicio, p.fecha_fin, p.estado_prestamo, s.nombre AS sede FROM prestamos p "
            . "JOIN usuarios u ON p.usuario_id = u.id_usuario "
            . "LEFT JOIN sedes s ON p.sede_id = s.id_sede "
            . "WHERE p.estado_prestamo = 'Autorizado' AND p.tipo_prestamo = 'Reservado'";

        $params = [];

        if (!empty($filtros)) {
            if (!empty($filtros['fecha_inicio']) && !empty($filtros['fecha_fin'])) {
                $sql .= " AND p.fecha_inicio >= :fecha_inicio AND p.fecha_fin <= :fecha_fin";
                $params[':fecha_inicio'] = $filtros['fecha_inicio'];
                $params[':fecha_fin'] = $filtros['fecha_fin'];
            }
            if (!empty($filtros['usuario'])) {
                $sql .= " AND u.nombre LIKE :usuario";
                $params[':usuario'] = "%" . $filtros['usuario'] . "%";
            }
            if (!empty($filtros['sede'])) {
                $sql .= " AND s.nombre LIKE :sede";
                $params[':sede'] = "%" . $filtros['sede'] . "%";
            }
        }

        $sql .= " ORDER BY p.fecha_inicio DESC";

        $stmt = Conexion::conectar()->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();

        return $stmt->fetchAll();
    }
}
