<?php

require_once "conexion.php";

class ModeloUsuarios
{

    static public function mdlCrearUsuario($tabla, $datos)
    {
        try {
            //Iniciar la transacción
            $conexion = Conexion::conectar();
            $conexion->beginTransaction();

            $stmt = $conexion->prepare("INSERT INTO $tabla(tipo_documento, numero_documento, nombre, apellido, correo_electronico, nombre_usuario, clave, telefono, direccion, genero, foto) VALUES (:tipo_documento, :documento, :nombre, :apellido, :email, :usuario, :clave, :telefono, :direccion, :genero, :foto)");

            $stmt->bindParam(":tipo_documento", $datos["tipo_documento"], PDO::PARAM_STR);
            $stmt->bindParam(":documento", $datos["documento"], PDO::PARAM_STR);
            $stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
            $stmt->bindParam(":apellido", $datos["apellido"], PDO::PARAM_STR);
            $stmt->bindParam(":email", $datos["email"], PDO::PARAM_STR);
            $stmt->bindParam(":usuario", $datos["usuario"], PDO::PARAM_STR);
            $stmt->bindParam(":clave", $datos["password"], PDO::PARAM_STR);
            $stmt->bindParam(":telefono", $datos["telefono"], PDO::PARAM_STR);
            $stmt->bindParam(":direccion", $datos["direccion"], PDO::PARAM_STR);
            $stmt->bindParam(":genero", $datos["genero"], PDO::PARAM_STR);
            $stmt->bindParam(":foto", $datos["foto"], PDO::PARAM_STR);

            $stmt->execute();


            //insertar los datos en la tabla usuario_rol
            $id_usuario = $conexion->lastInsertId();
            $stmt2 = $conexion->prepare("INSERT INTO usuario_rol(id_usuario, id_rol) VALUES (:id_usuario, :id_rol)");
            $stmt2->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
            $stmt2->bindParam(":id_rol", $datos["rol"], PDO::PARAM_INT);

            $stmt2->execute();



            //si el rol del usuario es 6 (aprendiz) se guarda el id de la ficha y el id del nuevo usuario en la tabla aprendices_ficha
            if ($datos["rol"] == "6") {
                $stmt3 = $conexion->prepare("INSERT INTO aprendices_ficha(id_usuario, id_ficha) VALUES (:id_usuario, :id_ficha)");
                $stmt3->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
                $stmt3->bindParam(":id_ficha", $datos["ficha"], PDO::PARAM_INT);

                $stmt3->execute();
            }

            //Confirmar transacción
            $conexion->commit();
            return "ok";
        } catch (Exception $e) {
            // Si ocurre un error, se revierte la transacción
            $conexion->rollBack();
            return "error";
        } finally {
            // Cerrar la conexión
            $conexion = null;
        }
    }


    static public function mdlMostrarUsuarios($tabla, $item, $valor)
    {

        if ($item != null) {
            $stmt = Conexion::conectar()->prepare("SELECT u.*, 
                                                            r.id_rol, r.nombre_rol, 
                                                            f.id_ficha, f.descripcion AS descripcion_ficha, f.codigo, f.estado AS estado_ficha,
                                                            s.id_sede, s.nombre_sede,
                                                            u.id_usuario,u.numero_documento, condicion
                                                            -- f.estado se llamo para mostrar el estado de la ficha en solicitudes
                                                            -- u.nombre,
                                                            -- u.apellido
                                                    FROM $tabla as u      
                                                    LEFT JOIN usuario_rol ur ON u.id_usuario = ur.id_usuario
                                                    LEFT JOIN roles r ON ur.id_rol = r.id_rol
                                                    LEFT JOIN aprendices_ficha af ON u.id_usuario = af.id_usuario
                                                    LEFT JOIN fichas f ON af.id_ficha = f.id_ficha 
                                                    LEFT JOIN sedes s ON f.id_sede = s.id_sede
                                                    WHERE u.$item = :$item LIMIT 1");
            if ($item == "id_usuario") {
                $stmt->bindParam(":" . $item, $valor, PDO::PARAM_INT);
            } else {
                $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);
            }
            $stmt->execute();
            return $stmt->fetch();
        } else {
            $stmt = Conexion::conectar()->prepare("SELECT u.*, r.id_rol, r.nombre_rol, f.id_ficha, f.descripcion AS descripcion_ficha, f.codigo
                                                    FROM $tabla as u      LEFT JOIN usuario_rol ur ON u.id_usuario = ur.id_usuario
                                                    LEFT JOIN roles r ON ur.id_rol = r.id_rol
                                                    LEFT JOIN aprendices_ficha af ON u.id_usuario = af.id_usuario
                                                    LEFT JOIN fichas f ON af.id_ficha = f.id_ficha;");
            $stmt->execute();
            return $stmt->fetchAll();
        }
        $stmt->close();
        $stmt = null;
    }

    static public function mdlMostrarFichasSede($tabla, $item, $valor)
    {

        if ($item != null) {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
            $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);
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


    /*=============================================
        EDITAR PERFIL
        =============================================*/
    static public function mdlEditarPerfil($tabla, $datos)
    {
        error_log("Consulta SQL: UPDATE $tabla SET tipo_documento = {$datos['tipo_documento']}, numero_documento = {$datos['numero_documento']}, nombre = {$datos['nombre']}, apellido = {$datos['apellido']}, correo_electronico = {$datos['correo_electronico']}, telefono = {$datos['telefono']}, direccion = {$datos['direccion']}, genero = {$datos['genero']}, clave = {$datos['clave']}, foto = {$datos['foto']} WHERE id_usuario = {$datos['id_usuario']}");
        try {
            $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET 
                    correo_electronico = :correo_electronico,
                    telefono = :telefono,
                    direccion = :direccion,
                    genero = :genero,
                    clave = :clave,
                    foto = :foto
                    WHERE id_usuario = :id_usuario");

            $stmt->bindParam(":correo_electronico", $datos["correo_electronico"], PDO::PARAM_STR);
            $stmt->bindParam(":telefono", $datos["telefono"], PDO::PARAM_STR);
            $stmt->bindParam(":direccion", $datos["direccion"], PDO::PARAM_STR);
            $stmt->bindParam(":genero", $datos["genero"], PDO::PARAM_STR);
            $stmt->bindParam(":clave", $datos["clave"], PDO::PARAM_STR);
            $stmt->bindParam(":foto", $datos["foto"], PDO::PARAM_STR);
            $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);

            if ($stmt->execute()) {
                return "ok";
            }

            return "error";
        } catch (PDOException $e) {
            return "error: " . $e->getMessage();
        } finally {
            if (isset($stmt)) {
                $stmt = null;
            }
        }
    }


    // Editar usuario con auditoría
    static public function mdlEditarUsuario($tabla, $datos)
    {
        try {
            $conexion = Conexion::conectar();
            $conexion->beginTransaction();

            // Setear el id del usuario que realiza la edición para auditoría (variable sesión MySQL)
            $conexion->exec("SET @id_usuario_editor = " . intval($datos["id_usuario_editor"]));

            // Actualizar datos básicos del usuario
            $stmt = $conexion->prepare(
                "UPDATE $tabla SET 
                    tipo_documento = :tipo_documento, 
                    numero_documento = :numero_documento, 
                    nombre = :nombre, 
                    apellido = :apellido, 
                    correo_electronico = :correo_electronico, 
                    telefono = :telefono, 
                    direccion = :direccion, 
                    genero = :genero, 
                    foto = :foto 
                WHERE id_usuario = :id_usuario"
            );

            $stmt->bindParam(":tipo_documento", $datos["tipo_documento"], PDO::PARAM_STR);
            $stmt->bindParam(":numero_documento", $datos["numero_documento"], PDO::PARAM_STR);
            $stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
            $stmt->bindParam(":apellido", $datos["apellido"], PDO::PARAM_STR);
            $stmt->bindParam(":correo_electronico", $datos["correo_electronico"], PDO::PARAM_STR);
            $stmt->bindParam(":telefono", $datos["telefono"], PDO::PARAM_STR);
            $stmt->bindParam(":direccion", $datos["direccion"], PDO::PARAM_STR);
            $stmt->bindParam(":genero", $datos["genero"], PDO::PARAM_INT);
            $stmt->bindParam(":foto", $datos["foto"], PDO::PARAM_STR);
            $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);

            $stmt->execute();

            // Actualizar o insertar rol si cambió
            if ($datos["idRolOriginal"] != $datos["id_rol"]) {
                // Verificar si ya existe un registro en usuario_rol
                $stmtCheck = $conexion->prepare("SELECT COUNT(*) FROM usuario_rol WHERE id_usuario = :id_usuario");
                $stmtCheck->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
                $stmtCheck->execute();
                $existe = $stmtCheck->fetchColumn();
                if ($existe) {
                    // Si existe, actualizar
                    $stmt2 = $conexion->prepare("UPDATE usuario_rol SET id_rol = :id_rol WHERE id_usuario = :id_usuario");
                    $stmt2->bindParam(":id_rol", $datos["id_rol"], PDO::PARAM_INT);
                    $stmt2->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
                    $stmt2->execute();
                } else {
                    // Si no existe, insertar
                    $stmt2 = $conexion->prepare("INSERT INTO usuario_rol(id_usuario, id_rol) VALUES (:id_usuario, :id_rol)");
                    $stmt2->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
                    $stmt2->bindParam(":id_rol", $datos["id_rol"], PDO::PARAM_INT);
                    $stmt2->execute();
                }
                // Cambiar estado a activo si se asigna o edita un rol
                $stmtEstado = $conexion->prepare("UPDATE $tabla SET estado = 'activo' WHERE id_usuario = :id_usuario");
                $stmtEstado->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
                $stmtEstado->execute();
            }

            // Si cambia de aprendiz a otro rol, eliminar ficha
            if ($datos["idRolOriginal"] == 6 && $datos["id_rol"] != 6) {
                $stmt3 = $conexion->prepare("DELETE FROM aprendices_ficha WHERE id_usuario = :id_usuario");
                $stmt3->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
                $stmt3->execute();
            }

            // Si cambia a aprendiz, insertar ficha
            if ($datos["idRolOriginal"] != 6 && $datos["id_rol"] == 6) {
                $stmt4 = $conexion->prepare("INSERT INTO aprendices_ficha(id_usuario, id_ficha) VALUES (:id_usuario, :id_ficha)");
                $stmt4->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
                $stmt4->bindParam(":id_ficha", $datos["id_ficha"], PDO::PARAM_INT);
                $stmt4->execute();
            }

            // Si sigue siendo aprendiz y cambia ficha, actualizar
            if ($datos["id_rol"] == 6 && $datos["idFichaOriginal"] != $datos["id_ficha"]) {
                $stmt5 = $conexion->prepare("UPDATE aprendices_ficha SET id_ficha = :id_ficha WHERE id_usuario = :id_usuario");
                $stmt5->bindParam(":id_ficha", $datos["id_ficha"], PDO::PARAM_INT);
                $stmt5->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
                $stmt5->execute();
            }

            $conexion->commit();
            return "ok";
        } catch (Exception $e) {
            $conexion->rollBack();
            error_log("Error al editar usuario: " . $e->getMessage());
            return "Error: " . $e->getMessage();
        } finally {
            $conexion = null;
        }
    }

    // Cambiar condición de usuario (solo admin puede cambiar)
    public static function mdlCambiarCondicionUsuario($tabla, $datos)
    {
        try {
            $stmt = Conexion::conectar()->prepare(
                "UPDATE $tabla SET condicion = :condicion WHERE id_usuario = :id_usuario"
            );

            $stmt->bindParam(":condicion", $datos["condicion"], PDO::PARAM_STR);
            $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);

            if ($stmt->execute()) {
                return "ok";
            }
            return "error";
        } catch (PDOException $e) {
            error_log("Error en mdlCambiarCondicionUsuario: " . $e->getMessage());
            return "error";
        }
    }

    // Cambiar estado usuario con auditoría
    static public function mdlCambiarEstadoUsuario($tabla, $datos)
    {
        try {
            $conexion = Conexion::conectar();

            // Setear id del usuario editor para auditoría
            $conexion->exec("SET @id_usuario_editor = " . intval($datos["id_usuario_editor"]));

            $stmt = $conexion->prepare("UPDATE $tabla SET estado = :estado WHERE id_usuario = :id_usuario");
            $stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_STR);
            $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);

            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            error_log("Error al cambiar estado usuario: " . $e->getMessage());
            return false;
        } finally {
            $conexion = null;
        }
    }

    static public function mdlObtenerRolesPorUsuario($idUsuario)
    {
        $stmt = Conexion::conectar()->prepare("SELECT * FROM usuario_rol WHERE id_usuario = :id_usuario");
        $stmt->bindParam(":id_usuario", $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    static public function mdlImportarUsuario($tabla, $datos)
    {
        $conexion = null; // Initialize $conexion to null
        try {
            //Iniciar la transacción
            $conexion = Conexion::conectar();
            $conexion->beginTransaction();

            $stmt = $conexion->prepare("INSERT INTO $tabla(tipo_documento, numero_documento, nombre, apellido, correo_electronico, nombre_usuario, clave, telefono, direccion, genero, foto, estado, condicion) VALUES (:tipo_documento, :documento, :nombre, :apellido, :email, :usuario, :clave, :telefono, :direccion, :genero, :foto, :estado, :condicion)");

            $stmt->bindParam(":tipo_documento", $datos["tipo_documento"], PDO::PARAM_STR);
            $stmt->bindParam(":documento", $datos["documento"], PDO::PARAM_STR);
            $stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
            $stmt->bindParam(":apellido", $datos["apellido"], PDO::PARAM_STR);
            $stmt->bindParam(":email", $datos["email"], PDO::PARAM_STR);
            $stmt->bindParam(":usuario", $datos["usuario"], PDO::PARAM_STR); // username is the document number
            $stmt->bindParam(":clave", $datos["password"], PDO::PARAM_STR);
            $stmt->bindParam(":telefono", $datos["telefono"], PDO::PARAM_STR);
            $stmt->bindParam(":direccion", $datos["direccion"], PDO::PARAM_STR);
            $stmt->bindParam(":genero", $datos["genero"], PDO::PARAM_STR);
            $stmt->bindParam(":foto", $datos["foto"], PDO::PARAM_STR);
            $stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_STR); // Added
            $stmt->bindParam(":condicion", $datos["condicion"], PDO::PARAM_STR); // Added

            $stmt->execute();

            //insertar los datos en la tabla usuario_rol
            $id_usuario = $conexion->lastInsertId();
            $stmt2 = $conexion->prepare("INSERT INTO usuario_rol(id_usuario, id_rol) VALUES (:id_usuario, :id_rol)");
            $stmt2->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
            $stmt2->bindParam(":id_rol", $datos["rol"], PDO::PARAM_INT);
            $stmt2->execute();

            //si el rol del usuario es 6 (aprendiz) se guarda el id de la ficha y el id del nuevo usuario en la tabla aprendices_ficha
            // Ensure 'ficha' and 'sede' keys exist if rol is 6, or handle potential notices.
            // The controller (ctrImportarUsuariosMasivo) should ensure $datos["ficha"] is set if $datos["rol"] == 6.
            if ($datos["rol"] == "6" && isset($datos["ficha"]) && !empty($datos["ficha"])) {
                $stmt3 = $conexion->prepare("INSERT INTO aprendices_ficha(id_usuario, id_ficha) VALUES (:id_usuario, :id_ficha)");
                $stmt3->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
                $stmt3->bindParam(":id_ficha", $datos["ficha"], PDO::PARAM_INT);
                $stmt3->execute();
            }

            //Confirmar transacción
            $conexion->commit();
            return "ok";
        } catch (Exception $e) {
            // Si ocurre un error, se revierte la transacción
            if ($conexion) { // Check if $conexion was initialized
                $conexion->rollBack();
            }
            // Log the error for debugging
            error_log("Error en mdlImportarUsuario: " . $e->getMessage());
            return "Error: " . $e->getMessage(); // Return specific error
        } finally {
            // Cerrar la conexión
            if ($conexion) {
                $conexion = null;
            }
        }
    }
}
