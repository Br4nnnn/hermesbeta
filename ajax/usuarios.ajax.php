<?php
session_start(); // <-- Added session_start()

require_once "../controladores/usuarios.controlador.php";
require_once "../modelos/usuarios.modelo.php";
require_once "../controladores/roles.controlador.php";
require_once "../modelos/roles.modelo.php";
require_once "../controladores/fichas.controlador.php";
require_once "../modelos/fichas.modelo.php";
require_once "../controladores/sedes.controlador.php";
require_once "../modelos/sedes.modelo.php";
require_once "../vendor/autoload.php"; // <-- Added for PhpSpreadsheet

class AjaxUsuarios
{

    public $sede;
    public $idUsuario;
    public $item;
    

    public function ajaxFichasSede()
    {
        $item = "sede";
        $valor = $_POST["sede"];
        $respuesta = ControladorUsuarios::ctrMostrarFichasSede($item, $valor);
        echo json_encode($respuesta);
    }


    public function ajaxMostrarUsuario()
    {
        // $item = "id_usuario";
        $valor = $this->idUsuario;
        $respuesta = ControladorUsuarios::ctrMostrarUsuarios($this->item, $valor);
        echo json_encode($respuesta);
    }

    public function ajaxImportarUsuariosMasivo()
    {
        // Assuming ctrImportarUsuariosMasivo() handles the import logic and returns a response
        $respuesta = ControladorUsuarios::ctrImportarUsuariosMasivo();
        echo $respuesta; // Assuming this returns a JSON response
        // echo json_encode($respuesta);
        exit; // Ensure to exit after echoing JSON to prevent further output
    }


}

// Bloque para importar usuarios masivamente
if(isset($_POST["accion"]) && $_POST["accion"] == "importarUsuariosMasivo"){
    $importar = new AjaxUsuarios();
    $importar->ajaxImportarUsuariosMasivo();
    // $importar = new ControladorUsuarios();
    // $importar->ctrImportarUsuariosMasivo();
    // It's important to exit here if ctrImportarUsuariosMasivo() echoes JSON and exits.
    // If it returns data, then this script would continue and potentially corrupt the JSON output.
    // For now, assuming ctrImportarUsuariosMasivo will handle echoing JSON and exiting.
}

if (isset($_POST["sede"])) {
    $fichas = new AjaxUsuarios();
    $fichas->sede = $_POST["sede"];
    $fichas->ajaxFichasSede();
}

if (isset($_POST["idUsuario"])) {
    $usuario = new AjaxUsuarios();
    $usuario->idUsuario = $_POST["idUsuario"];
    $usuario->item = "id_usuario";
    $usuario->ajaxMostrarUsuario();
} 

if (isset($_POST["idSolicitante"])) {
    $solicitante = new AjaxUsuarios();
    $solicitante->idUsuario = $_POST["idSolicitante"];
    $solicitante->item = "numero_documento";
    $solicitante->ajaxMostrarUsuario();
}
// Cambiar estado de usuario

if (isset($_POST["idUsuarioEstado"], $_POST["estado"])) {
    $id = $_POST["idUsuarioEstado"];
    $estado = $_POST["estado"];
    $respuesta = ControladorUsuarios::ctrCambiarEstadoUsuario($id, $estado);
    echo $respuesta ? 'ok' : 'error';
    exit;
}

// Cambiar condicion del usuario

if (isset($_POST["idUsuarioCondicion"], $_POST["condicion"])) {

    $respuesta = ControladorUsuarios::ctrCambiarCondicionUsuario(
        $_POST["idUsuarioCondicion"],
        $_POST["condicion"]
    );
    
    echo $respuesta;
    exit;
}