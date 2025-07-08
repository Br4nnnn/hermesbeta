<?php

class ControladorAutorizaciones {

    //controlador mostrar autorizaciones en tabla principal
    static public function ctrMostrarAutorizaciones($item, $valor){
        $tabla = "autorizaciones";
        $respuesta = ModeloAutorizaciones::mdlMostrarAutorizaciones($tabla, $item, $valor);
        return $respuesta;
    }

    static public function ctrRechazarPrestamo(){
        if(isset($_POST["motivoRechazoAutorizacion"]) && isset($_POST["numeroPrestamoRechazar"]) && isset($_POST["idUsuarioRechazar"]) && isset($_POST["idRolRechazar"])){
            $tabla = "autorizaciones";
            $datos = array("id_prestamo"=>$_POST["numeroPrestamoRechazar"],
                           "motivo_rechazo"=>$_POST["motivoRechazoAutorizacion"],
                           "id_usuario"=>$_POST["idUsuarioRechazar"],
                           "id_rol"=>$_POST["idRolRechazar"]);

            $respuesta = ModeloAutorizaciones::mdlRechazarPrestamo($tabla, $datos);
            // return $respuesta;
            if($respuesta == "ok"){
                echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "Prestamo rechazado",
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location = "autorizaciones";
                });
                </script>';
            }else{
              echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "'.$respuesta.'",
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location = "autorizaciones";
                });
                </script>';  
            }
            
        }
    }

    static public function ctrListarPrestamosAutorizados($filtros = []) {
        $respuesta = ModeloAutorizaciones::mdlListarPrestamosAutorizados($filtros);
        return $respuesta;
    }

}