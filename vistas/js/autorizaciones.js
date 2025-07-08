//funcion para traer las autorizaciones del prestamo
function traerAutorizaciones(idPrestamo, estadoPrestamo) {
  //traemos las autorizaciones de ese mismo prestamo
  console.log("idPrestamoFuncion :", idPrestamo);
  datosAutorizaciones = new FormData();
  datosAutorizaciones.append("accion", "mostrarAutorizaciones");
  datosAutorizaciones.append("idPrestamo", idPrestamo);
  $.ajax({
    url: "ajax/autorizaciones.ajax.php",
    method: "POST",
    data: datosAutorizaciones,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuestaAutorizaciones) {
      console.log("AUTORIZACIONES :", respuestaAutorizaciones);
      $("#alertaRechazado").addClass("d-none");
      $(".btnAccionFirma").addClass("d-none");
      $(".btnDesautorizar").addClass("d-none");

      let nombreRolSesion = $("#nombre_rolSesion").val();
      let idUsuarioSesion = $("#id_UsuarioSesion").val();

      let firmaCoordinacion = respuestaAutorizaciones["firma_coordinacion"];
      let firmaTIC = respuestaAutorizaciones["firma_lider_tic"];
      let firmaAlmacen = respuestaAutorizaciones["firma_almacen"];

      let idCoordinacion = respuestaAutorizaciones["id_usuario_coordinacion"];
      let idLiderTIC = respuestaAutorizaciones["id_usuario_lider_tic"];
      let idAlmacen = respuestaAutorizaciones["id_usuario_almacen"];

      if (estadoPrestamo != "Autorizado") {
        if (estadoPrestamo == "Rechazado") {
          $("#usuarioNombreRechaza").text();
          $("#usuarioNombreRechaza").text(
            respuestaAutorizaciones["usuario_que_rechazo"]
          );
          $("#alertaRechazado").removeClass("d-none");
        } else {
          // BOTON AUTORIZAR - DESAUTORIZAR - RECHAZAR
          //si tiene el rol para firmar y el rol no ha firmado, puede autorizar o rechazar
          if (
            (nombreRolSesion == "Coordinación" &&
              firmaCoordinacion != "Firmado") ||
            (nombreRolSesion == "Líder TIC" && firmaTIC != "Firmado") ||
            (nombreRolSesion == "Almacén" && firmaAlmacen != "Firmado")
          ) {
            $(".btnAccionFirma").removeClass("d-none");
          }
          //si tiene el rol para firmar, si el rol ya ha firmado y fue el mismo usuario el que firmo, puede desautorizar
          if (
            (nombreRolSesion == "Coordinación" &&
              firmaCoordinacion == "Firmado" &&
              idCoordinacion == idUsuarioSesion) ||
            (nombreRolSesion == "Líder TIC" &&
              firmaTIC == "Firmado" &&
              idLiderTIC == idUsuarioSesion) ||
            (nombreRolSesion == "Almacén" &&
              firmaAlmacen == "Firmado" &&
              idAlmacen == idUsuarioSesion)
          ) {
            $(".btnDesautorizar").removeClass("d-none");
          }
        }
      }
    },
  });
}

//para ver detalle del prestamo
$(document).on("click", ".btnVerDetallePrestamo_Autorizar", function () {
  let idPrestamo = $(this).attr("idPrestamo");
  //   console.log("idPrestamo :", idPrestamo);
  datos = new FormData();
  datos.append("accion", "mostrarPrestamo");
  datos.append("idPrestamo", idPrestamo);
  $.ajax({
    url: "ajax/solicitudes.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      // console.log("PRESTAMO :", respuesta);
      $("#numeroPrestamo").text(respuesta["id_prestamo"]);
      $("#userRol").text(respuesta["nombre_rol"]);

      // Mostrar solo la fecha (yyyy-mm-dd), sin la hora
      let fechaInicio = respuesta["fecha_inicio"]
        ? respuesta["fecha_inicio"].split(" ")[0]
        : "";
      let fechaFin = respuesta["fecha_fin"]
        ? respuesta["fecha_fin"].split(" ")[0]
        : "";
      $("#detalleFechaInicio").text(fechaInicio);
      $("#detalleFechaFin").text(fechaFin);
      $("#detalleMotivoPrestamo").text(respuesta["motivo"]);
      $("#estadoPrestamo").text(respuesta["estado_prestamo"]);
      datosUsuario = new FormData();
      datosUsuario.append("idUsuario", respuesta["id_usuario"]);
      //traemos los datos del usuario
      $.ajax({
        url: "ajax/usuarios.ajax.php",
        method: "POST",
        data: datosUsuario,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (respuestaUsuario) {
          console.log("USUARIO :", respuestaUsuario);
          //colocamos los datos del usuario
          $("#usuarioNombre").text(
            respuestaUsuario["nombre"] + " " + respuestaUsuario["apellido"]
          );
          $("#usuarioIdentificacion").text(
            respuestaUsuario["tipo_documento"] +
              " " +
              respuestaUsuario["numero_documento"]
          );
          $("#usuarioTelefono").text(respuestaUsuario["telefono"]);
          if (respuestaUsuario["nombre_rol"] == "Aprendiz") {
            $("#usuarioFicha").text(respuestaUsuario["codigo"]);
          } else {
            $("#usuarioFicha").text("N/A");
          }

          //colocamos la imagen del usuario
          if (respuestaUsuario["foto"] != "") {
            $("#imgUsuario").attr("src", respuestaUsuario["foto"]);
          } else {
            $("#imgUsuario").attr(
              "src",
              "vistas/img/usuarios/default/anonymous.png"
            );
          }
        },
      });

      datosDetalle = new FormData();
      datosDetalle.append("accion", "mostrarPrestamoDetalle");
      datosDetalle.append("idPrestamoDetalle", respuesta["id_prestamo"]);
      $.ajax({
        url: "ajax/solicitudes.ajax.php",
        method: "POST",
        data: datosDetalle,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (respuestaDetalle) {
          //colocamos los datos en el datatable
          $("#tblDetallePrestamo").DataTable().clear().destroy();
          $("#tblDetallePrestamo").DataTable({
            data: respuestaDetalle,
            columns: [
              { data: "equipo_id" },
              { data: "categoria" },
              { data: "descripcion" },
              { data: "etiqueta" },
              { data: "numero_serie" },
              { data: "ubicacion" },
            ],
            responsive: true,
            lengthChange: true,
            pagin: true,
            searching: true,
            ordering: true,
            info: true,            
            language: {
              sProcessing: "Procesando...",
              sLengthMenu: "Mostrar _MENU_ registros",
              sZeroRecords: "No se encontraron resultados",
              sEmptyTable: "Ningún dato disponible en esta tabla",
              sInfo:
                "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
              sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0",
              sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
              search: "Buscar:",
              paginate: {
                first: "Primero",
                last: "Último",
                next: "Siguiente",
                previous: "Anterior",
              },
            }
            
          });
        },
      });
      traerAutorizaciones(
        respuesta["id_prestamo"],
        respuesta["estado_prestamo"]
      );
    },
  });
});

//autorizar el prestamo reservado
$(document).on("click", ".btnAutorizar", function () {
  let idPrestamo = $("#numeroPrestamo").text();
  let id_rol = $("#idRolSesion").val();
  let id_usuario = $("#id_UsuarioSesion").val();
  console.log("idPrestamo :", idPrestamo);
  console.log("id_rol :", id_rol);
  console.log("id_usuario :", id_usuario);
  // debugger;
  datos = new FormData();
  datos.append("accion", "autorizarReserva");
  datos.append("idPrestamo", idPrestamo);
  datos.append("id_rol", id_rol);
  datos.append("id_usuario", id_usuario);
  $.ajax({
    url: "ajax/autorizaciones.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta :", respuesta);
      if (respuesta == "ok") {
        Swal.fire({
          icon: "success",
          title: "Autorizado",
          text: "Se autorizo el prestamo",
          showConfirmButton: false,
          timer: 1500,
          willClose: () => {
            window.location = "autorizaciones";
          },
        });
      } else {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "No se pudo autorizar el prestamo",
          showConfirmButton: false,
          timer: 1500,
        });
      }
    },
  });
});

//quitar la firma de la autorizacion
$(document).on("click", ".btnDesautorizar", function () {
  let idPrestamo = $("#numeroPrestamo").text();
  let id_rol = $("#idRolSesion").val();
  let id_usuario = $("#id_UsuarioSesion").val();
  // console.log("idPrestamo :", idPrestamo);
  // console.log("id_rol :", id_rol);
  // console.log("id_usuario :", id_usuario);
  datos = new FormData();
  datos.append("accion", "desautorizarReserva");
  datos.append("idPrestamo", idPrestamo);
  datos.append("id_rol", id_rol);
  datos.append("id_usuario", id_usuario);
  $.ajax({
    url: "ajax/autorizaciones.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta :", respuesta);
      if (respuesta == "ok") {
        Swal.fire({
          icon: "success",
          title: "Desautorizado",
          text: "ha quitado su autorización del prestamo",
          showConfirmButton: false,
          timer: 1500,
          willClose: () => {
            window.location = "autorizaciones";
          },
        });
      } else {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "No se pudo desautorizar el prestamo",
          showConfirmButton: false,
          timer: 1500,
        });
      }
    },
  });
});

$(document).on("click", ".btnRechazar", function () {
  let idPrestamo = $("#numeroPrestamo").text();
  $("#numeroPrestamoRechazar").val(idPrestamo);
});

//tooltip
$("[title]").tooltip();


$(document).ready(function() {
  // Función para cargar préstamos autorizados con filtros
  function cargarPrestamosAutorizados(filtros = {}) {
    $.ajax({
      url: 'ajax/autorizaciones.ajax.php',
      method: 'POST',
      data: {
        accion: 'listarPrestamosAutorizados',
        filtros: filtros
      },
      dataType: 'json',
      success: function(respuesta) {
        let tabla = $('#tblPrestamosAutorizados').DataTable();
        tabla.clear();
        respuesta.forEach(function(prestamo) {
          tabla.row.add([
            prestamo.id_prestamo,
            prestamo.solicitante,
            prestamo.fecha_inicio,
            prestamo.fecha_fin,
            prestamo.estado_prestamo,
            prestamo.sede || '',
            `<button class=\"btn btn-info btnVerDetallePrestamo_Autorizar\" idPrestamo=\"${prestamo.id_prestamo}\" data-toggle=\"modal\" data-target=\"#modalVerDetallesPrestamo\"><i class=\"fas fa-eye\"></i></button>`
          ]);
        });
        tabla.draw();
      }
    });
  }

  // Inicializar DataTable para préstamos autorizados
  $('#tblPrestamosAutorizados').DataTable({
    responsive: true,
    lengthChange: true,
    searching: true,
    ordering: true,
    paging: true,
    info: true,
    language: {
      sProcessing: 'Procesando...',
      sLengthMenu: 'Mostrar _MENU_ registros',
      sZeroRecords: 'No se encontraron resultados',
      sEmptyTable: 'Ningún dato disponible en esta tabla',
      sInfo: 'Mostrando registros del _START_ al _END_ de un total de _TOTAL_',
      sInfoEmpty: 'Mostrando registros del 0 al 0 de un total de 0',
      sInfoFiltered: '(filtrado de un total de _MAX_ registros)',
      search: 'Buscar:',
      paginate: {
        first: 'Primero',
        last: 'Último',
        next: 'Siguiente',
        previous: 'Anterior'
      }
    }
  });

  // Evento para abrir modal y cargar datos
  $('#btnVerAutorizados').on('click', function() {
    cargarPrestamosAutorizados();
  });

  // Evento para aplicar filtros
  $('#btnAplicarFiltros').on('click', function() {
    let filtros = {
      fechaInicio: $('#filtroFechaInicio').val(),
      fechaFin: $('#filtroFechaFin').val(),
      usuario: $('#filtroUsuario').val(),
      sede: $('#filtroSede').val()
    };
    cargarPrestamosAutorizados(filtros);
  });
});
