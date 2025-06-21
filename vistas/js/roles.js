$(document).on("click", ".btnEditarRol", function() {
    var idRol = $(this).attr("idRol");
    var datos = new FormData();
    datos.append("idRol", idRol);
    $.ajax({
        url: "ajax/roles.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(respuesta) {
            console.log("respuesta", respuesta);
            $("#nombreEditRol").val(respuesta["nombre_rol"]);
            $("#descripcionEditRol").val(respuesta["descripcion"]);
            $("#idEditRol").val(respuesta["id_rol"]);
        },
    });
});

$(document).on("click", ".btnActivarRol", function() {
    var idRolActivar = $(this).attr("idRol");
    var estadoRol = $(this).attr("estadoRol");
    var datos = new FormData();
    datos.append("idRolActivar", idRolActivar);
    datos.append("estadoRol", estadoRol);
    $.ajax({
        url: "ajax/roles.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success: function(respuesta) {
            console.log("cambiado el estado", respuesta);
        }
    })
    if (estadoRol == "inactivo") {
        $(this).removeClass("btn-success");
        $(this).addClass("btn-danger");
        $(this).html('Inactivo');
        $(this).attr("estadoRol", "activo");
    }else {
        $(this).removeClass("btn-danger");
        $(this).addClass("btn-success");
        $(this).html('Activo');
        $(this).attr("estadoRol", "inactivo");
    }
});

$(document).on("click", ".btnEliminarRol", function() {
    var idRol = $(this).attr("idRol");
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡Esta acción no se puede deshacer!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, Eliminar el rol',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            var datos = new FormData();
            datos.append("idRolEliminar", idRol);
            $.ajax({
                url: "ajax/roles.ajax.php",
                method: "POST",
                data: datos,
                cache: false,
                contentType: false,
                processData: false,
                success: function(respuesta) {
                    Swal.fire('Eliminado', 'El rol ha sido eliminado y los usuarios asignados han sido inhabilitados.', 'success').then(()=>{

                        location.reload();
                    });
                }
            });
        }
    });
});