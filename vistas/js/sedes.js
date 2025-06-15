$(document).on("click", ".btnEditarSede", function () {
// $(".btnEditarSede").click(function () {
    var idSede = $(this).attr("idSede");
    var datos = new FormData();
    datos.append("idSede", idSede);
    $.ajax({
        url: "ajax/sedes.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (respuesta) {
            // console.log("SEDEEEEEED", respuesta);
            $("#nombreEditSede").val(respuesta["nombre_sede"]);
            $("#direccionEditSede").val(respuesta["direccion"]);
            $("#descripcionEditSede").val(respuesta["descripcion"]);
            $("#idEditSede").val(respuesta["id_sede"]);
        },
    });
});

$(document).on("click", ".btnActivarSede", function () {
    var idSedeActivar = $(this).attr("idSede");
    var estadoSede = $(this).attr("estadoSede");
    var datos = new FormData();
    datos.append("idSedeActivar", idSedeActivar);
    datos.append("estadoSede", estadoSede);
    
    $.ajax({
        url: "ajax/sedes.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success: function (respuesta) {
            console.log("cambiado el estado", respuesta);
        }
    })
    if (estadoSede == "inactiva") {
        $(this).removeClass("btn-success");
        $(this).addClass("btn-danger");
        $(this).html('Inactiva');
        $(this).attr("estadoSede", "activa");
    }else {
        $(this).removeClass("btn-danger");
        $(this).addClass("btn-success");
        $(this).html('Activa');
        $(this).attr("estadoSede", "inactiva");
    }
});

//tooltip
$(document).ready(function () {
    $('[data-bs-toggle="tooltip"]').tooltip();
});
