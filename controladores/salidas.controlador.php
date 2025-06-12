<?php


class Controladorsalidas
{
    /*=============================================
    MOSTRAR DEVOLUCIONES (LISTADO)
    =============================================*/
    static public function ctrMostrarsalidas($item, $valor)
    {
        $tabla = "prestamos";
        $respuesta = Modelosalida::mdlMostrarsalida($tabla, $item, $valor);
        return $respuesta;
    }

    static public function ctrAceptarSalida()
    {
        // error_log("Entrando en ctrAceptarSalida");
        if (isset($_POST["idUsuarioAutorizaSalida"])) {
            error_log("ID de préstamo: ". $_POST["idPrestamoSalida"]);
            error_log("ID de usuario: ". $_POST["idUsuarioAutorizaSalida"]);

            $respuesta = Modelosalida::mdlPrestarPrestamo($_POST["idPrestamoSalida"]);

            $tabla = "salidas";
            $datos = array(
                "id_prestamo" => $_POST["idPrestamoSalida"],
                "id_usuario" => $_POST["idUsuarioAutorizaSalida"]
            );

            $respuesta = Modelosalida::mdlAceptarSalida($tabla, $datos);
            error_log("Respuesta del modelo: " . $respuesta);
            if ($respuesta == "ok") {

                echo '<script>
                    Swal.fire({
                        icon: "success",
                        title: "¡Salida aceptada!",
                        text: "La salida ha sido aceptada correctamente.",
                        confirmButtonText: "Aceptar"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location = "salidas";
                        }
                    });
                </script>';
            } else {
                echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Error al aceptar la salida",
                        text: "' . $respuesta . '",
                        confirmButtonText: "Aceptar"
                    });
                </script>';
            }
        }
    }
}
