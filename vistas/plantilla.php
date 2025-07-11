<?php

session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Hermes 2847523 Beta</title>

  <!-- Google Font: Source Sans Pro -->
  <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"> -->
  <!-- icons  -->
  <link rel="apple-touch-icon" sizes="180x180" href="vistas/img/Logo/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="vistas/img/Logo/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="vistas/img/Logo/favicon-16x16.png">
  <link rel="manifest" href="vistas/img/Logo/site.webmanifest">
  <link rel="shortcut icon" href="vistas/img/Logo/favicon.ico">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="vistas/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- daterange picker -->
  <link rel="stylesheet" href="vistas/plugins/daterangepicker/daterangepicker.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="vistas/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="vistas/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="vistas/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="vistas/dist/css/adminlte.css">
  <!-- mi css -->
  <link rel="stylesheet" href="vistas/css/plantilla.css">
  <!-- summernote -->
  <link rel="stylesheet" href="vistas/plugins/summernote/summernote-bs4.min.css">

  <!-- ================================================================================================== -->

  <!-- jQuery -->
  <script src="vistas/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="vistas/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- DataTables  & Plugins  -->
  <script src="vistas/plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="vistas/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
  <script src="vistas/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
  <script src="vistas/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
  <script src="vistas/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
  <script src="vistas/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
  <script src="vistas/plugins/jszip/jszip.min.js"></script>
  <script src="vistas/plugins/pdfmake/pdfmake.min.js"></script>
  <script src="vistas/plugins/pdfmake/vfs_fonts.js"></script>
  <script src="vistas/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
  <script src="vistas/plugins/datatables-buttons/js/buttons.print.min.js"></script>
  <script src="vistas/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
  <!-- Summernote -->
  <script src="vistas/plugins/summernote/summernote-bs4.min.js"></script>
  <!-- InputMask -->
  <script src="vistas/plugins/moment/moment.min.js"></script>
  <script src="vistas/plugins/inputmask/jquery.inputmask.min.js"></script>

  <!-- date-range-picker -->
  <script src="vistas/plugins/daterangepicker/daterangepicker.js"></script>

  <!-- SweetAlert2 -->
  <!-- <script src="vistas/plugins/sweetalert2/sweetalert2.min.js"></script> -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- AdminLTE App -->
  <script src="vistas/dist/js/adminlte.min.js"></script>
  

  <!-- CSS para evitar FOUC (Flash of Unstyled Content) -->
  <style>
    body {
      visibility: hidden;
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    body.loaded {
      visibility: visible;
      opacity: 1;
    }
  </style>

</head>

<body class="hold-transition sidebar-mini login-page">

  <?php

  if (isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"] == "ok") {

    echo '<script>
      document.addEventListener("DOMContentLoaded", function() {
        document.body.classList.remove("login-page");
      });
    </script>';

    echo '<script>
        const usuarioActual = {
            id: ' . $_SESSION['id_usuario'] . ',
            cedula: ' . $_SESSION['numero_documento'] . ',
            permisos: ' . json_encode($_SESSION['permisos']) . '  
        }
    </script>';

    echo '<div class="wrapper">';
    include "modulos/cabezote.php";
    include "modulos/menu.php";

    if (isset($_GET["ruta"])) {
      if (
        $_GET["ruta"] == "inicio" ||
        $_GET["ruta"] == "fichas" ||
        $_GET["ruta"] == "sedes" ||
        $_GET["ruta"] == "usuarios" ||
        $_GET["ruta"] == "roles" ||
        $_GET["ruta"] == "modulos" ||
        $_GET["ruta"] == "permisos" ||
        $_GET["ruta"] == "inventario" ||
        $_GET["ruta"] == "recepcion" ||
        $_GET["ruta"] == "solicitudes" ||
        $_GET["ruta"] == "consultar-solicitudes" ||
        $_GET["ruta"] == "mis-solicitudes" ||
        $_GET["ruta"] == "autorizaciones" ||
        $_GET["ruta"] == "devoluciones" ||
        $_GET["ruta"] == "salidas" ||
        $_GET["ruta"] == "reportes" ||
        $_GET["ruta"] == "Mantenimiento" ||
        $_GET["ruta"] == "reporte-equipos" ||
        $_GET["ruta"] == "desactivado" ||
        $_GET["ruta"] == "auditoria" ||
        $_GET["ruta"] == "notificaciones" ||
        $_GET["ruta"] == "redactar" ||
        $_GET["ruta"] == "salir"
      ) {

        include "modulos/" . $_GET["ruta"] . ".php";
      } else {
        include "modulos/error404.php";
      }
    }

    include "modulos/footer.php";
    echo '</div>';
  } else {
    include "modulos/login.php";
  }
  ?>



  <script src="vistas/js/plantilla.js"></script>
  <script src="vistas/js/sedes.js"></script>
  <script src="vistas/js/fichas.js"></script>
  <script src="vistas/js/roles.js"></script>
  <script src="vistas/js/permisos.js"></script>
  <script src="vistas/js/modulos.js"></script>
  <script src="vistas/js/usuarios.js"></script>
  <script src="vistas/js/autorizaciones.js"></script>
  <script src="vistas/js/solicitudes.js"></script>
  <script src="vistas/js/equipos.js"></script>
  <script src="vistas/js/consultar-solicitudes.js"></script>
  <script src="vistas/js/devoluciones.js"></script>
  <!-- <script src="vistas/js/auditoria.js"></script> -->
  <script src="vistas/js/salidas.js"></script>
  <script src="vistas/js/mis-solicitudes.js"></script>
  <script src="vistas/js/notificaciones.js"></script>

  <!-- JS para evitar FOUC (Flash of Unstyled Content) -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      document.body.classList.add('loaded');
    });
  </script>


</body>

</html>