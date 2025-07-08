<?php

require_once "../controladores/autorizaciones.controlador.php";
require_once "../modelos/autorizaciones.modelo.php";

class AjaxAutorizaciones
{
    // /*=============================================
    // VARIABLES
    // =============================================*/
    // public $idAutorizacion;
    // public $estado;
    // public $id_usuario;
    public $idPrestamo;
    public $idRol;
    public $idUsuario;

    // /*=============================================
    // MOSTRAR DETALLES DE AUTORIZACIÓN
    // =============================================*/
    // public function ajaxMostrarAutorizacion()
    // {
    //     $item = "id_autorizacion";
    //     $valor = $this->idAutorizacion;

    //     $respuesta = ControladorAutorizaciones::ctrMostrarAutorizaciones($item, $valor);
    //     echo json_encode($respuesta);
    // }

    public function ajaxAutorizarPrestamo(){
        
        $idPrestamo = $this->idPrestamo;
        $idRol = $this->idRol;
        $idUsuario = $this->idUsuario;

        $respuesta = ModeloAutorizaciones::mdlAutorizarPrestamo($idPrestamo, $idRol, $idUsuario);

        echo json_encode($respuesta);
        
    }

    public function ajaxDesautorizarPrestamo(){

        $idPrestamo = $this->idPrestamo;
        $idRol = $this->idRol;
        $idUsuario = $this->idUsuario;

        $respuesta = ModeloAutorizaciones::mdlDesautorizarPrestamo($idPrestamo, $idRol, $idUsuario);

        echo json_encode($respuesta);
    }

    public function ajaxMostrarAutorizaciones()
    {
        $item = "id_prestamo";
        $valor= $this->idPrestamo;
        $respuesta = ControladorAutorizaciones::ctrMostrarAutorizaciones($item, $valor);
        echo json_encode($respuesta);
    }

}

/*=============================================
MOSTRAR DETALLES DE AUTORIZACIÓN
=============================================*/
// if (isset($_POST["idAutorizacion"]) && !isset($_POST["estado"])) {
//     $autorizar = new AjaxAutorizaciones();
//     $autorizar->idAutorizacion = $_POST["idAutorizacion"];
//     $autorizar->ajaxMostrarAutorizacion();
// }


if (isset($_POST["accion"])) {

    $autorizar = new AjaxAutorizaciones();

    switch ($_POST["accion"]) {
        case 'autorizarReserva':
            $autorizar->idPrestamo = $_POST["idPrestamo"];
            $autorizar->idRol = $_POST["id_rol"];
            $autorizar->idUsuario = $_POST["id_usuario"];
            $autorizar->ajaxAutorizarPrestamo();
            break;

        case 'desautorizarReserva':
            $autorizar->idPrestamo = $_POST["idPrestamo"];
            $autorizar->idRol = $_POST["id_rol"];
            $autorizar->idUsuario = $_POST["id_usuario"];
            $autorizar->ajaxDesautorizarPrestamo();
            break;

        case 'mostrarAutorizaciones':
            $autorizar->idPrestamo = $_POST["idPrestamo"];
            $autorizar->ajaxMostrarAutorizaciones();
            break;
            
        case 'listarPrestamosAutorizados':
            $filtros = isset($_POST['filtros']) ? $_POST['filtros'] : [];
            $respuesta = ControladorAutorizaciones::ctrListarPrestamosAutorizados($filtros);
            echo json_encode($respuesta);
            break;
    }
}
