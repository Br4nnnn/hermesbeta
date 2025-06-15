$(document).on("click", ".btnAutorizarSolicitud", function () {
  const idSolicitud = $(this).data("id");
  const rol = $(this).data("rol"); // Obtener rol del data attribute

  Swal.fire({
    title: `¿Estas Seguro De Autorizar Esta Solicitud?`,
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#dc3545",
    cancelButtonText: "Cancelar",
    confirmButtonText: "Autorizar",
    reverseButtons: true,
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "ajax/autorizaciones.ajax.php",
        method: "POST",
        data: {
          id: idSolicitud,
          accion: "autorizar",
          rol: rol, // Usar variable JS
        },
        success: function (respuesta) {
          try {
            respuesta = JSON.parse(respuesta);
            Swal.fire(respuesta.titulo, respuesta.mensaje, respuesta.estado);
          } catch (e) {
            Swal.fire("Error", "Respuesta inválida del servidor", "error");
          }
        },
        error: function () {
          Swal.fire("Error", "Error de conexión", "error");
        },
      });
    }
  });
});

$(document).on("click", ".btnRechazarSolicitud", function () {
  const idSolicitud = $(this).data("id");
  const rol = $(this).data("rol");

  Swal.fire({
    title: "¿Seguro deseas rechazar esta solicitud?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#dc3545",
    cancelButtonColor: "#6c757d",
    cancelButtonText: "Cancelar",
    confirmButtonText: "Rechazar",
    reverseButtons: true,
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "ajax/autorizaciones.ajax.php",
        method: "POST",
        data: {
          id: idSolicitud,
          accion: "rechazar",
          rol: rol,
        },
        success: function (respuesta) {
          try {
            respuesta = JSON.parse(respuesta);
            Swal.fire(respuesta.titulo, respuesta.mensaje, respuesta.estado);
          } catch (e) {
            Swal.fire("Error", "Respuesta inválida del servidor", "error");
          }
        },
        error: function () {
          Swal.fire("Error", "Error de conexión", "error");
        },
      });
    }
  });
});

//ventana modal para ver detalle del prestamo
$(document).ready(function () {
  // Cuando se hace clic en el botón Ver
  $(".btnVerUsuario").click(function () {
    var idPrestamo = $(this).attr("data-id");

    // Realizar petición AJAX
    $.ajax({
      url: "ajax/devoluciones.ajax.php",
      method: "POST",
      data: {
        idPrestamo: idPrestamo,
      },
      dataType: "json",
      success: function (respuesta) {
        if (respuesta && respuesta.length > 0) {
          var datosPrestamo = respuesta[0];

          // Imagen y datos básicos del usuario
          $("#userImage").attr(
            "src",
            datosPrestamo.foto
              ? datosPrestamo.foto
              : "vistas/img/usuarios/default/anonymous.png"
          );
          $("#userName").text(
            datosPrestamo.nombre + " " + datosPrestamo.apellido
          );
          $("#userRol").text(datosPrestamo.nombre_rol || "No especificado");
          $("#userName").text(
            datosPrestamo.nombre_usuario + " " + datosPrestamo.apellido_usuario
          );
          $("#userRol").text(datosPrestamo.nombre_rol || "No especificado"); // Updated to use nombre_rol

          // Información del préstamo
          $("#prestamoIdentificacion").text(
            datosPrestamo.numero_documento || "No disponible"
          );
          $("#prestamoNombre").text(datosPrestamo.nombre || "No disponible");
          $("#prestamoApellido").text(
            datosPrestamo.apellido || "No disponible"
          );
          $("#prestamoTelefono").text(
            datosPrestamo.telefono || "No disponible"
          );
          $("#prestamoFicha").text(datosPrestamo.ficha_codigo || "No asignada");
          $("#prestamoTipo").text(
            datosPrestamo.tipo_prestamo || "No especificado"
          );
          $("#prestamoFechaInicio").text(
            datosPrestamo.fecha_inicio || "No especificada"
          );
          $("#prestamoFechaFin").text(
            datosPrestamo.fecha_fin || "No especificada"
          );
          $("#prestamoEstado").text(
            datosPrestamo.estado_prestamo || "No especificado"
          );

          // Información de los equipos en una tabla
          var equiposTableHtml = `
                        <table class="table table-bordered table-striped dt-responsive tablaDevolucionesEquipos" width="100%">
                            <thead>
                                <tr>
                                    <th>Categoría</th>
                                    <th>Marca</th>
                                    <th>Placa</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;

          respuesta.forEach(function (equipo) {
            equiposTableHtml += `
                            <tr>
                                <td>${
                                  equipo.categoria_nombre || "No disponible"
                                }</td>
                                <td>${
                                  equipo.marca_equipo || "No disponible"
                                }</td> 
                                <td>${
                                  equipo.placa_equipo || "No disponible"
                                }</td>
                                <td class="equipo-buttons-container">`;

            // Lógica para mostrar los botones de devolución según el tipo de préstamo
            if (datosPrestamo.tipo_prestamo === "Inmediato") {
              equiposTableHtml += `
                                <button type="button" class="btn btn-success btn-sm btn-devolver-equipo mr-1" data-prestamo-id="${datosPrestamo.id_prestamo}" data-equipo-id="${equipo.equipo_id}" data-estado="buen_estado">
                                    <i class="fas fa-check-circle"></i> B. Estado
                                </button>
                                <button type="button" class="btn btn-danger btn-sm btn-devolver-equipo" data-prestamo-id="${datosPrestamo.id_prestamo}" data-equipo-id="${equipo.equipo_id}" data-estado="mal_estado">
                                    <i class="fas fa-times-circle"></i> M. Estado
                                </button>
                            `;
            } else if (datosPrestamo.tipo_prestamo === "Reservado") {
              equiposTableHtml += `
                                <button type="button" class="btn btn-info btn-sm btn-devolver-equipo" data-prestamo-id="${datosPrestamo.id_prestamo}" data-equipo-id="${equipo.equipo_id}" data-estado="devuelto">
                                    <i class="fas fa-undo-alt"></i> Devolver
                                </button>
                                <button type="button" class="btn btn-danger btn-sm btn-devolver-equipo" data-prestamo-id="${datosPrestamo.id_prestamo}" data-equipo-id="${equipo.equipo_id}" data-estado="robado">
                                    <i class="fas fa-times-circle"></i> Robado
                                </button>
                            `;
            }
            equiposTableHtml += `</td></tr>`;
          });

          equiposTableHtml += `
                            </tbody>
                        </table>
                    `;

          $("#equiposListContainer").html(equiposTableHtml);
          $("#modalVerDetallesPrestamo").modal("show");
        } else {
          alert("No se encontraron datos de equipos para este préstamo.");
          $("#equiposListContainer").html(
            "<p>No hay equipos asociados a este préstamo.</p>"
          );
        }
      },
      error: function (xhr, status, error) {
        console.error("Error en la petición AJAX:", error);
        alert("Error al cargar los datos del préstamo");
      },
    });
  });
});

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
      console.log("PRESTAMO :", respuesta);
      $("#numeroPrestamo").text(respuesta["id_prestamo"]);
      $("#detalleTipoPrestamo").text(respuesta["tipo_prestamo"]);
      $("#detalleFechaInicio").text(respuesta["fecha_inicio"]);
      $("#detalleFechaFin").text(respuesta["fecha_fin"]);
      $("#detalleMotivoPrestamo").text(respuesta["motivo"]);
      $("#estadoPrestamo").text(respuesta["estado_prestamo"]);

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
          console.log("DETALLE :", respuestaDetalle);
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
            autoWidth: false,
            scrollX: true,
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
            },
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
