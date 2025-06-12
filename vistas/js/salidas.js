function cargarDetallesPrestamo(idPrestamo) {
    $.ajax({
        url: "ajax/prestamos.ajax.php",
        method: "POST",
        data: {
            idPrestamo: idPrestamo,
            accion: "obtenerDetalles"
        },
        dataType: 'json',
        success: function(respuesta) {
            if (respuesta) {
                $("#modalIdPrestamo").text(respuesta.id_prestamo);
                $("#modalUsuario").text(respuesta.usuario_id);
                $("#modalTipo").text(respuesta.tipo_prestamo);
                $("#modalFechaInicio").text(respuesta.fecha_inicio);
                $("#modalFechaFin").text(respuesta.fecha_fin);
                $("#modalEstado").text(respuesta.estado_prestamo);
                $("#modalMotivo").text(respuesta.motivo);
            }
        }
    });
}
$(document).on('click', '.btnVerDetalles', function() {
    var idPrestamo = $(this).data('id');
    $.ajax({
        url: "ajax/solicitudes.ajax.php",
        method: "POST",
        data: { accion: "mostrarPrestamo", idPrestamo: idPrestamo },
        dataType: "json",
        success: function(respuesta) {
            // Rellenar los campos de la modal
            $("#numeroPrestamo, #numeroPrestamo").text(respuesta["id_prestamo"]);
            $("#idPrestamoSalida").val(respuesta["id_prestamo"]);
            $("#detalleTipoPrestamo").text(respuesta["estado_prestamo"]);
            $("#detalleFechaInicio").text(respuesta["fecha_inicio"]);
            $("#detalleFechaFin").text(respuesta["fecha_fin"]);
            $("#detalleMotivoPrestamo").text(respuesta["motivo"]);
            $("#detalleUsuarioNombre").text(respuesta["nombre_usuario"]);
            $("#detalleUsuarioRol").text(respuesta["nombre_rol"]);
           

            // Mostrar u ocultar el botón "Aceptar" según el estado
            if (respuesta["estado_prestamo"] === "Autorizado") {
                $("#btnAceptarPrestamo").removeClass("d-none");
            } else {
                $("#btnAceptarPrestamo").addClass("d-none");
            }


            // Cargar detalles de equipos
            $.ajax({
                url: "ajax/solicitudes.ajax.php",
                method: "POST",
                data: { accion: "mostrarPrestamoDetalle", idPrestamoDetalle: respuesta["id_prestamo"] },
                dataType: "json",
                success: function(detalles) {
                    var tbody = $("#tblDetallePrestamo tbody");
                    tbody.empty();
                    detalles.forEach(function(equipo) {
                        tbody.append(
                            "<tr>" +
                            "<td>" + equipo.equipo_id + "</td>" +
                            "<td>" + equipo.categoria + "</td>" +
                            "<td>" + equipo.descripcion + "</td>" +
                            "<td>" + equipo.etiqueta + "</td>" +
                            "<td>" + equipo.numero_serie + "</td>" +
                            "<td>" + equipo.ubicacion + "</td>" +
                            "</tr>"
                        );
                    });
                }
            });
        }
    });
});
