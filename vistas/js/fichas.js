/* ==================================================
SERVERSIDE EQUIPOS
================================================== */

$('#tblFichas').DataTable({
    "processing": true,
    "serverSide": true,
    "sAjaxSource": "ajax/serverside/serverside.fichas.php",
    "columns": [
        { "data": null },
        { "data": "1" },
        { "data": "2" },
        { "data": "3" },
        { "data": "4" },
        { "data": "5" },
        { "data": "6" },
        { "data": null }
    ],
    "columnDefs": [
        {
            "targets": [0],
            "render": function(data, type, row, meta) {
                return meta.row + 1;
            },
        },
        {
            "targets": [6],
            "render": function(data, type, row) {
                if (data === "activa") {
                    return "<button  class='btn btn-success btn-xs btnActivarFicha' idFicha='" + row[0] + "' estadoFicha='inactiva' title='Ficha activa' data-toggle='tooltip'><i class='fas fa-check'></i></button>";
                } else {
                    return "<button  class='btn btn-danger btn-xs btnActivarFicha' idFicha='" + row[0] + "' estadoFicha='activa' title='Ficha inactiva' data-toggle='tooltip'><i class='fas fa-ban'></i></button>";                    
                }
            }
        },

        {
            "targets": [-1],
            "render": function(row) {
                
            return "<div class='btn-group'>" +
                "<button title='Editar datos ficha' data-tooltip='tooltip' class='btn btn-default btn-xs btnEditarFicha' idFicha='" + row[0] + "' data-toggle='modal' data-target='#modalEditFicha'>" +
                "<i class='fas fa-edit '></i>" +
                "</button></div>";

                 
            }
        }
    ],
    "responsive": true,
    "autoWidth": false,
    "lengthChange": true,
    "lengthMenu":[10, 25, 50, 100],
    "language": {
        "lengthMenu": "Mostrar _MENU_ registros",
        "zeroRecords": "No se encontraron resultados",
        "info": "Mostrando pagina _PAGE_ de _PAGES_",
        "infoEmpty": "No hay registros disponibles",
        "infoFiltered": "(filtrado de _MAX_ total registros)",
        "search": "Buscar:",
        "paginate": {
          "first":      "Primero",
          "last":       "Ultimo",
          "next":       "Siguiente",
          "previous":   "Anterior"
        },
    },
    "buttons": ["csv", "excel", "pdf"],
    "dom": "lfBrtip"    
});

$(document).on("click", ".btnEditarFicha", function() {
// $(".btnEditarFicha").click(function() {
    var idFicha = $(this).attr("idFicha");
    var datos = new FormData();
    datos.append("idFicha", idFicha);
    $.ajax({
        url: "ajax/fichas.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(respuesta) {
            // console.log("Ficha", respuesta["nom"]);
            $("#idEditFicha").val(respuesta["id_ficha"]);
            $("#editCodigoFicha").val(respuesta["codigo"]);
            $("#editDescripcionFicha").val(respuesta["descripcion"]);
            $("#editFechaInicioFicha").val(respuesta["fecha_inicio"]);
            $("#editFechaFinFicha").val(respuesta["fecha_fin"]);
            
            $("#editSedeFicha").val(respuesta["id_sede"]);
            $("#editSedeFicha").html(respuesta["nombre_sede"]);
            
        },
    });
});

$(document).on("click", ".btnActivarFicha", function() {
    var idFichaActivar = $(this).attr("idFicha");
    var estadoFicha = $(this).attr("estadoFicha");
    var datos = new FormData();
    datos.append("idFichaActivar", idFichaActivar);
    datos.append("estadoFicha", estadoFicha);
    $.ajax({
        url: "ajax/fichas.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success: function(respuesta) {
            console.log("cambiado el estado", respuesta);
        }
    })
    if (estadoFicha == "inactiva") {
        $(this).removeClass("btn-success");
        $(this).addClass("btn-danger");
        $(this).html('<i class="fas fa-ban">');
        $(this).attr("estadoFicha", "activa");
    }else {
        $(this).removeClass("btn-danger");
        $(this).addClass("btn-success");
        $(this).html('<i class="fas fa-check">');
        $(this).attr("estadoFicha", "inactiva");
    }
});

//tooltips
// Activar tooltips después de cada renderizado de tabla
$('#tblFichas').on('draw.dt', function () {
    $('[data-toggle="tooltip"]').tooltip();
});
