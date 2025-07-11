//para ver detalle del prestamo
$(document).on("click", ".btnVerDetalle", function () {
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
    //   console.log("Prestamo :", respuesta);
      $("#numeroPrestamo").text(respuesta["id_prestamo"]);
      $("#detalleTipoPrestamo").text(respuesta["tipo_prestamo"]);
      $("#detalleFechaInicio").text(respuesta["fecha_inicio"]);
      $("#detalleFechaFin").text(respuesta["fecha_fin"]);
      $("#detalleMotivoPrestamo").text(respuesta["motivo"]);
    
       
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
            console.log("respuestaDetalle :",respuestaDetalle);
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
              responsive: false,
              autoWidth: false,      
              ordering: true,        
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
             
            })
          }
        })
    },
  });
});

//tooltip
$('[title]').tooltip();

