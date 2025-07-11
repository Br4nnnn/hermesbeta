//************************************************************
// 
//  SERVERSIDE USUARIOS
// 
//************************************************************/
$('#tblUsuarios').DataTable({
    // destroy: true,
    "processing": true,
    "serverSide": true,
    "sAjaxSource": "ajax/serverside/serverside.usuarios.php",
    "columns": [
        { "data": null},
        { "data": "1" },
        { "data": "2" },
        { "data": "3" },
        { "data": "4" },
        { "data": "5" },
        { "data": "6" },
        { "data": "7" },
        { "data": "8" },
        { "data": "9" },
        { "data": null}      
    ],
    "columnDefs": [
        {
            "targets": [0],
            "render": function(data, type, row, meta) {
                return meta.row + 1;
            }
        },
        {
            "targets": [8],
            "render": function(data, type, row) {
                if(usuarioActual["permisos"].includes(40)){ // Validación #40:  Permite activar y desactivar al usuario
                    if (data === "activo") {
                        return "<button title='Inactivar usuario' class='btn btn-success btnActivarUsuario' data-id='" + row[0] + "' data-estado='inactivo'>Activo</button>";
                    } else {
                        return "<button title='Activar usuario' class='btn btn-danger btnActivarUsuario' data-id='" + row[0] + "' data-estado='activo'>Inactivo</button>";                    
                    }
                } else {
                    if (data === "activo") {
                        return "<button title='Inactivar usuario' class='btn btn-success' disabled data-id='" + row[0] + "'>Activo</button>";
                    } else {
                        return "<button title='Activar usuario' class='btn btn-danger' disabled data-id='" + row[0] + "'>Inactivo</button>";                    
                    }
                }
            }
        },
        {
            "targets": [-1],
            "render": function(data, type, row) {
                let botones = "<div class='btn-group'>";
                // Validación #37: Permite el acceso a los detalles del usuario
                if(usuarioActual["permisos"].includes(37)){
                    botones += "<button title='Consultar detalles de usuario' class='btn btn-default btnConsultarUsuario' idUsuario='" + row[0] + "' data-toggle='modal' data-target='#modalConsularUsuario'><i class='fas fa-eye'></i></button>";
                }
                // Validación #38: Permite editar los datos del usuario
                if(usuarioActual["permisos"].includes(38)){
                    botones += "<button title='Editar usuario' class='btn btn-default btnEditarUsuario' idUsuario='" + row[0] + "' data-toggle='modal' data-target='#modalEditarUsuario'><i class='fas fa-edit'></i></button>";
                }
                // Validación #39: Permite ver las solicitudes del usuario
                if(usuarioActual["permisos"].includes(39)){
                    botones += "<button title='Solicitudes del usuario' class='btn btn-default btnSolicitudesUsuario' idUsuario='" + row[0] + "' data-numero-documento='"+ row[2] +"' data-toggle='modal' data-target='#modalSolicitudesUsuario'><i class='fas fa-laptop'></i></button>";
                }
                botones += "</div>";
                return botones;
            }
        },
        {
            "targets": [9],
            "render": function(data, type, row) {
                let condicion = row[9];
                let idUsuario = row[0];
                if(usuarioActual["permisos"].includes(41)){ // Validación #41:  Permite alternar las diferentes condiciones de usuario (En regla, Advertido, Penalizado)
                    if (condicion === "en_regla") {
                        return `<button class="btn btn-success  btnCambiarCondicionUsuario" idUsuario="${idUsuario}" condicionUsuario="advertido">En regla</button>`;
                    } else if (condicion === "advertido") {
                        return `<button class="btn btn-warning  btnCambiarCondicionUsuario" idUsuario="${idUsuario}" condicionUsuario="penalizado">Advertido</button>`;
                    } else if (condicion === "penalizado") {
                        return `<button class="btn btn-danger  btnCambiarCondicionUsuario" idUsuario="${idUsuario}" condicionUsuario="en_regla">Penalizado</button>`;
                    } else {
                        return '';
                    }
                } else {
                    if (condicion === "en_regla") {
                        return `<button class="btn btn-success" disabled idUsuario="${idUsuario}">En regla</button>`;
                    } else if (condicion === "advertido") {
                        return `<button class="btn btn-warning" disabled idUsuario="${idUsuario}">Advertido</button>`;
                    } else if (condicion === "penalizado") {
                        return `<button class="btn btn-danger" disabled idUsuario="${idUsuario}">Penalizado</button>`;
                    } else {
                        return '';
                    }
                }
            }
        },


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
    "buttons": ["csv", "excel"],
    "dom": "lfBrtip"
});   


//************************************************************
// 
//  SCRIPT PARA AGREGAR USUARIOS
// 
//************************************************************/

$(document).on("change", "#selectRol", function() {
    var idRol = $(this).val();
    console.log(idRol);
    // si el rol es aprendiz, mostrar los inputs de ficha y sede
    if (idRol == 6) {
        $("#sede").removeClass("d-none");
        $("#selectSede").prop("required", true);
        $("#id_ficha").prop("required", true);
        // $("#ficha").removeClass("d-none");
    } else {
        $("#sede").addClass("d-none");
        $("#ficha").addClass("d-none");
        $("#selectSede").removeAttr("required");
        $("#id_ficha").removeAttr("required");

        // $("#ficha").addClass("d-none");
    }
});

//************************************************************
// 
//  Condicion de los usuarios (solo admin puede cambiar)
// 
//************************************************************/

$(document).on('click', '.btnCambiarCondicionUsuario', function() {
    const boton = $(this);
    const datos = {
        idUsuarioCondicion: boton.attr("idUsuario"),
        condicion: boton.attr("condicionUsuario")
    };

    $.ajax({
        url: "ajax/usuarios.ajax.php",
        method: "POST",
        data: datos,
        success: response => manejarRespuestaCondicion(response, boton),
        error: () => manejarErrorCondicion(boton)
    });
});

// Función para mostrar feedback visual más estético al cambiar condición

function toggleBotonLoading(boton, estado) {
    if (estado) {
        // Mostrar spinner y desactivar botón temporalmente
        boton.data('original-html', boton.html());
        boton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
    } else {
        // Queda en el estado original el boton si se da click y no es administrador
        const originalHtml = boton.data('original-html');
        if (originalHtml !== undefined) {
            boton.html(originalHtml);
            boton.removeData('original-html');
        }
        boton.prop('disabled', false);
    }
}

// Función para manejar la respuesta de la condición del usuario

function manejarRespuestaCondicion(respuesta, boton) {
    if (respuesta.trim() === "ok") {
        actualizarBotonCondicion(boton, boton.attr("condicionUsuario"));
    } else if (respuesta.trim() === "acceso_denegado") {
        Toast.fire({
            icon: 'error',
            title: 'Acceso denegado'
        });
        toggleBotonLoading(boton, false);
    } else {
        toggleBotonLoading(boton, false);
    }
}

// Función para manejar errores de conexión

function manejarErrorCondicion(boton) {
    Toast.fire({
        icon: 'error',
        title: 'Error de conexión'
    });
    toggleBotonLoading(boton, false);
}

// constantes para los estados de condición del usuario

const ESTADOS_CONDICION = {
    en_regla: {
        removeClasses: 'btn-warning btn-danger',
        addClass: 'btn-success',
        texto: 'En regla',
        siguiente: 'advertido'
    },
    advertido: {
        removeClasses: 'btn-success btn-danger',
        addClass: 'btn-warning',
        texto: 'Advertido',
        siguiente: 'penalizado'
    },
    penalizado: {
        removeClasses: 'btn-success btn-warning',
        addClass: 'btn-danger',
        texto: 'Penalizado',
        siguiente: 'en_regla'
    }
};

// Función para actualizar el botón de condición del usuario

function actualizarBotonCondicion(boton, nuevaCondicion) {
    const estado = ESTADOS_CONDICION[nuevaCondicion];
    if (!estado) return;

    boton
        .removeClass(estado.removeClasses)
        .addClass(estado.addClass)
        .html(estado.texto)
        .attr('condicionUsuario', estado.siguiente)
        .prop('disabled', false);
}

//************************************************************
// script para cambiar los estados de los usuarios
//************************************************************/
$(document).on('click', '.btnActivarUsuario', function () {
    var idUsuario = $(this).data('id');
    var estadoActual = $(this).data('estado'); // el estado al que se va a cambiar
    var boton = $(this);

    // Solo validar si se va a activar
    if (estadoActual === "activo") {
        // Consultar si el usuario tiene roles asociados
        $.ajax({
            url: "ajax/usuarios.ajax.php",
            method: "POST",
            data: { idUsuarioRoles: idUsuario },
            dataType: "json",
            success: function (respuesta) {
                if (respuesta.length === 0) {
                    Swal.fire({
                        icon: "warning",
                        title: "No se puede activar",
                        text: "El usuario no tiene un rol asignado. Asigne un rol antes de activar.",
                    });
                    return; // No continúa con la activación
                } else {
                    // Si tiene roles, proceder con la activación
                    cambiarEstadoUsuario(boton, idUsuario, estadoActual);
                }
            },
            error: function () {
                Swal.fire("Error", "Fallo de conexión al verificar roles", "error");
            }
        });
    } else {
        // Si se va a desactivar, no hace falta validar roles
        cambiarEstadoUsuario(boton, idUsuario, estadoActual);
    }
});

// Función para cambiar el estado del usuario (tu lógica original)
function cambiarEstadoUsuario(boton, idUsuario, estadoActual) {
    // Desactivar temporalmente el botón
    boton.prop('disabled', true);

    var textoOriginal = boton.html();
    boton.html('<i class="fas fa-spinner fa-spin"></i>');

    $.ajax({
        url: "ajax/usuarios.ajax.php",
        method: "POST",
        data: {
            idUsuarioEstado: idUsuario,
            estado: estadoActual
        },
        success: function (respuesta) {
            if (respuesta.trim() === "ok") {
                // Cambiar estado visualmente sin recargar
                if (estadoActual === "activo") {
                    boton.removeClass('btn-danger').addClass('btn-success');
                    boton.text('Activo');
                    boton.data('estado', 'inactivo');
                } else {
                    boton.removeClass('btn-success').addClass('btn-danger');
                    boton.text('Inactivo');
                    boton.data('estado', 'activo');
                }
                boton.prop('disabled', false);
            } else if (respuesta.trim() === "error_sin_rol") {
                Swal.fire({
                    icon: "warning",
                    title: "No se puede activar",
                    text: "El usuario no tiene un rol asignado. Asigne un rol antes de activar.",
                });
                boton.prop('disabled', false).html(textoOriginal);
            } else {
                Swal.fire("Error", "No se pudo cambiar el estado", "error");
                boton.prop('disabled', false).html(textoOriginal);
            }
        }
    });
}


$(document).on("change", "#selectSede", function() {
    var idSede = $(this).val();
    console.log("Sede seleccionada:", idSede);

    // Verifica si se seleccionó una sede válida
    if (!idSede) {
        console.warn("No se seleccionó una sede válida.");
        $("#id_ficha").empty().append('<option value="">Seleccione una ficha</option>');
        $("#ficha").addClass("d-none");
        return;
    }

    var datos = new FormData();
    datos.append("idSede", idSede);

    // Realiza una petición AJAX para obtener las fichas de la sede seleccionada
    $.ajax({
        url: "ajax/fichas.ajax.php", // Archivo PHP que devolverá las fichas
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(respuesta) {
            // console.log("Fichas obtenidas:", respuesta);

            var fichaSelect = $("#id_ficha");
            fichaSelect.empty();
            // console.log("registros: ",      respuesta[0]);


            // Verifica si se recibieron fichas
            if (!respuesta || respuesta.length === 0 || respuesta[0] == null || respuesta[0] == undefined) {
                console.warn("No se encontraron fichas para la sede seleccionada.");
                $("#id_ficha").empty().append('<option value="">No hay fichas disponibles</option>');
                $("#ficha").addClass("d-none");
            } else if (Array.isArray(respuesta) && respuesta.length > 0) { //Se se reciben varias fichas
                // console.log("Respuesta es un array y tiene elementos:", respuesta);
                fichaSelect.append('<option value="">Ficha</option>');
                // Agrega las nuevas opciones al select de ficha
                respuesta.forEach(function(ficha) {
                    fichaSelect.append('<option value="' + ficha.id_ficha + '">' + ficha.codigo + '</option>');
                });
                $("#ficha").removeClass("d-none");
            } else { //Se recibe una sola ficha
                // console.log("Respuesta no es un array o tiene un solo elemento:", respuesta);
                fichaSelect.append('<option value="' + respuesta["id_ficha"] + '">' + respuesta["codigo"] + '</option>');
                $("#nombre_programa").prop("placeholder", respuesta["descripcion"]);
                $("#ficha").removeClass("d-none");
            }
        },
        error: function(error) {
            console.error("Error al obtener las fichas:", error);
            // Manejo de error: Limpia el select y oculta el contenedor
            $("#id_ficha").empty().append('<option value="">Error al cargar fichas</option>');
            $("#ficha").addClass("d-none");
        }
    });
});


// ======================================
// PREVISUALIZACIÓN DE IMAGEN (EDITAR PERFIL)
// ======================================

function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('vistaPreviaFoto');
    const file = input.files[0];

    // Validar tipo y tamaño de archivo
    if (file) {
        if (!file.type.match('image/jpeg') && !file.type.match('image/png')) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'El archivo debe ser una imagen JPG o PNG'
            });
            input.value = '';
            return;
        }

        if (file.size > 2 * 1024 * 1024) { // 2MB
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'La imagen no debe superar los 2MB'
            });
            input.value = '';
            return;
        }

        // Mostrar vista previa
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
}

// ======================================
// SOLICITUDES DE PRESTAMO DEL USUARIO
// ======================================
$(document).on("click", ".btnSolicitudesUsuario", function() {
    // Obtener el número de documento del usuario seleccionado
    var numeroDocumento = $(this).data("numero-documento");
    var idUsuario = $(this).data("id-usuario");

    if (!numeroDocumento) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se encontró el número de documento del usuario'
        });
        return;
    }

    // Redirigir a consultar-solicitudes con los parámetros necesarios
    // Construir la URL base
    let redirectUrl = "consultar-solicitudes?";
    
    // Agregar parámetros usando el nombre de parámetro 'cedula' 
    // para compatibilidad con la función existente
    redirectUrl += "cedula=" + encodeURIComponent(numeroDocumento) + 
                  "&origin=usuarios" +
                  "&autoBuscar=1";
    
    // Redireccionar
    window.location.href = redirectUrl;
});

// ======================================
// AUTOCOMPLETAR BÚSQUEDA DE USUARIO
// ======================================
$(document).ready(function() {
    // Obtener parámetros de la URL
    const urlParams = new URLSearchParams(window.location.search);
    const numeroDocumento = urlParams.get('numeroDocumento');
    const autoBuscar = urlParams.get('autoBuscar');

    // Si tenemos el número de documento y autoBuscar es true
    if (numeroDocumento && autoBuscar === "1") {
        // Establecer el valor en el input
        $("#cedulaUsuario").val(numeroDocumento);

        // Simular click en el botón de búsqueda después de un pequeño delay
        setTimeout(function() {
            const btnBuscar = $("#btnBuscarUsuarioConsultar");
            if (btnBuscar.length) {
                btnBuscar.trigger('click');
            } else {
                console.error("No se encontró el botón de búsqueda");
            }
        }, 500); // Esperar 500ms para asegurar que todo esté cargado
    }
});


// ======================================
// SCRIPT PARA CONSULTAR USUARIOS
// ======================================
$(document).on("click", ".btnConsultarUsuario", function() {
    var idUsuario = $(this).attr("idUsuario");

    var datos = new FormData();
    datos.append("idUsuario", idUsuario);

    $.ajax({
        url: "ajax/usuarios.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(respuesta) {
            $("#idConsultarUsuario").val(respuesta.id_usuario);
            $("#consultarNombre").val(respuesta.nombre);
            $("#consultarApellido").val(respuesta.apellido);
            $("#consultarTipoDocumento").val(respuesta.tipo_documento);
            $("#consultarNumeroDocumento").val(respuesta.numero_documento);
            $("#consultarRol").val(respuesta.nombre_rol);
            $("#consultarEmail").val(respuesta.correo_electronico);
            $("#consultarTelefono").val(respuesta.telefono);
            $("#consultarDireccion").val(respuesta.direccion);

            // Mostrar género si existe
            if (respuesta.genero !== undefined && respuesta.genero !== null) {
                let generoTexto = '';
                switch (parseInt(respuesta.genero)) {
                    case 1:
                        generoTexto = 'Femenino';
                        break;
                    case 2:
                        generoTexto = 'Masculino';
                        break;
                    case 3:
                        generoTexto = 'No definido / Prefiere no decir';
                        break;
                    case 0:
                    default:
                        generoTexto = 'No declara';
                        break;
                }
                $("#consultarGenero").val(generoTexto);
            } else if (respuesta.genero_texto !== undefined && respuesta.genero_texto !== null) {
                $("#consultarGenero").val(respuesta.genero_texto);
            } else {
                $("#consultarGenero").val('');
            }

            // Mostrar sede y ficha si el rol es aprendiz 
            if (respuesta.id_rol == 6) {
                $("#consultarSedeFicha").removeClass("d-none");
                $("#consultarSede").val(respuesta.nombre_sede || '');

                // Mostrar ficha 
                let ficha = respuesta.codigo_ficha || respuesta.codigo || '';
                let programa = respuesta.nombre_programa || respuesta.descripcion || respuesta.descripcion_ficha || '';
                $("#consultarFicha").val(ficha && programa ? ficha + " - " + programa : ficha || programa);
            } else {
                $("#consultarSedeFicha").addClass("d-none");
                $("#consultarSede").val('');
                $("#consultarFicha").val('');
            }


            // Mostrar foto relacionado de la bd de datos (ruta)
            if (respuesta.foto && respuesta.foto !== "") {
                $("#consultarFotoUsuario").attr("src", respuesta.foto).removeClass("d-none");
            } else {
                $("#consultarFotoUsuario").attr("src", "vistas/img/usuarios/default/anonymous.png").removeClass("d-none");
            }
        }
    });
});

//************************************************************
// 
//  SCRIPT PARA EDITAR USUARIOS
// 
//************************************************************/

$(document).on("change", "#selectEditRolUsuario", function() {
    var idRol = $(this).val();
    console.log(idRol);
    // si el rol es aprendiz, mostrar los inputs de ficha y sede
    if (idRol == 6) {
        $("#editSede").removeClass("d-none");
        $("#selectEditSede").prop("required", true);
        $("#selectEditIdFicha").prop("required", true);
        // $("#ficha").removeClass("d-none");
    } else {
        $("#editSede").addClass("d-none");
        $("#EditFicha").addClass("d-none");
        $("#selectEditSede").removeAttr("required");
        $("#selectEditIdFicha").removeAttr("required");
        // $("#ficha").addClass("d-none");
    }
});

// Mostrar foto de usuario al editar
$(document).on("click", ".btnEditarUsuario", function() {
    var idUsuario = $(this).attr("idUsuario") || $(this).attr("idusuario");
    var datos = new FormData();
    datos.append("idUsuario", idUsuario);
    $.ajax({
        url: "ajax/usuarios.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(respuesta) {
            // ... (otros campos)
            if (respuesta["foto"] && respuesta["foto"].trim() !== "") {
                $("#editFotoUsuario").attr("src", respuesta["foto"]).removeClass("d-none");
            } else {
                $("#editFotoUsuario").attr("src", "vistas/img/usuarios/default/anonymous.png").removeClass("d-none");
            }
        }
    });
});

$(document).on("change", "#selectEditSede", function() {

    var idSede = $(this).val();
    // var inicial = $(this).attr("inicial");

    console.log("Sede a editar seleccionada:", idSede);


    var datos = new FormData();
    datos.append("idSede", idSede);

    // Realiza una petición AJAX para obtener las fichas de la sede seleccionada
    $.ajax({
        url: "ajax/fichas.ajax.php", // Archivo PHP que devolverá las fichas
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(respuesta) {
            console.log("Fichas obtenidas:", respuesta);

            var fichaSelect = $("#selectEditIdFicha");
            fichaSelect.empty();



            // Verifica si se recibieron fichas
            if (!respuesta || respuesta.length === 0 || respuesta[0] == null || respuesta[0] == undefined) {
                console.warn("No se encontraron fichas para la sede seleccionada.");
                $("#selectEditIdFicha").empty().append('<option value="">No hay fichas disponibles</option>');
                Toast.fire({
                    icon: 'error',
                    title: 'No hay fichas disponibles para la sede seleccionada.'
                });
                $("#EditFicha").addClass("d-none");
            } else if (Array.isArray(respuesta) && respuesta.length > 0) { //Se se reciben varias fichas
                // Agrega las nuevas opciones al select de ficha
                fichaSelect.append('<option value="">Ficha</option>');
                $("#nombreEditPrograma").prop("placeholder", "Programa");
                respuesta.forEach(function(ficha) {
                    fichaSelect.append('<option value="' + ficha.id_ficha + '">' + ficha.codigo + '</option>');
                });
                $("#EditFicha").removeClass("d-none");
            } else { //Se recibe una sola ficha
                // console.log("Respuesta no es un array o tiene un solo elemento:", respuesta);
                fichaSelect.append('<option value="' + respuesta["id_ficha"] + '">' + respuesta["codigo"] + '</option>');
                $("#nombreEditPrograma").prop("placeholder", respuesta["descripcion"]);
                $("#EditFicha").removeClass("d-none");
            }
            $("#selectEditSede").attr("inicial", "false");
        },
        error: function(error) {
            console.error("Error al obtener las fichas:", error);
            // Manejo de error: Limpia el select y oculta el contenedor
            $("#selectEditIdFicha").empty().append('<option value="">Error al cargar fichas</option>');
            $("#EditFicha").addClass("d-none");
        }
    });
})


$(document).on("change", "#id_ficha", function() {
    var idFicha = $(this).val();
    console.log("Ficha seleccionada:", idFicha);
    data = new FormData();
    data.append("idFicha", idFicha);
    // Realiza una petición AJAX para obtener los datos de la ficha seleccionada
    $.ajax({
        url: "ajax/fichas.ajax.php", // Archivo PHP que devolverá los datos de la ficha
        method: "POST",
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(respuesta) {
            // console.log("Datos de la ficha:", respuesta);
            $("#nombre_programa").prop("placeholder", respuesta["descripcion"]);
            // Aquí puedes realizar cualquier acción adicional que necesites con los datos de la ficha
        },
        error: function(error) {
            console.error("Error al obtener los datos de la ficha:", error);
            // Manejo de error
        }
    });
})


$(document).on("change", "#selectEditIdFicha", function() {
    var idFicha = $(this).val();
    console.log("Ficha seleccionada:", idFicha);
    data = new FormData();
    data.append("idFicha", idFicha);
    // Realiza una petición AJAX para obtener los datos de la ficha seleccionada
    $.ajax({
        url: "ajax/fichas.ajax.php", // Archivo PHP que devolverá los datos de la ficha
        method: "POST",
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(respuesta) {
            // console.log("Datos de la ficha:", respuesta);
            $("#nombreEditPrograma").prop("placeholder", respuesta["descripcion"]);
        },
        error: function(error) {
            console.error("Error al obtener los datos de la ficha:", error);
            // Manejo de error
        }
    });
})

$(document).on("click", ".btnEditarUsuario", function() {

    var idUsuario = $(this).attr("idUsuario") || $(this).attr("idusuario");
    var datos = new FormData();
    datos.append("idUsuario", idUsuario);
    $.ajax({
        url: "ajax/usuarios.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(respuesta) {
            console.log("Usuario", respuesta);
            $("#idEditUsuario").val(respuesta["id_usuario"]);
            $("#editNombre").val(respuesta["nombre"]);
            $("#editApellido").val(respuesta["apellido"]);
            $("#editTipoDocumento").val(respuesta["tipo_documento"]);
            $("#editTipoDocumento").html(respuesta["tipo_documento"]);
            $("#editNumeroDocumento").val(respuesta["numero_documento"]);
            $("#rolOriginal").val(respuesta["id_rol"]);
            $("#EditRolUsuario").val(respuesta["id_rol"]);
            $("#EditRolUsuario").html(respuesta["nombre_rol"]);

            if (respuesta["id_rol"] == 6) {
                $("#editSede").removeClass("d-none");
                $("#EditFicha").removeClass("d-none");
                $("#selectEditSede").prop("required", true);
                $("#selectEditIdFicha").prop("required", true);
            } else {
                $("#editSede").addClass("d-none");
                $("#EditFicha").addClass("d-none");
                $("#selectEditSede").removeAttr("required");
                $("#selectEditIdFicha").removeAttr("required");
            }

            $("#selectEditSede").attr("idSede", respuesta["id_sede"]);
            $("#optionEditSede").val(respuesta["id_sede"]);
            $("#optionEditSede").html(respuesta["nombre_sede"]);
            //$("#selectEditSede").trigger("change");
            //disparar el evento change para cargar las fichas de la sede seleccionada
            // $("#selectEditSede").trigger("change");

            $("#optionEditIdFicha").val(respuesta["id_ficha"]);
            $("#optionEditIdFicha").html(respuesta["codigo"]);
            $("#editEmail").val(respuesta["correo_electronico"]);
            $("#editTelefono").val(respuesta["telefono"]);
            $("#editDireccion").val(respuesta["direccion"]);
            $("#nombreEditPrograma").prop("placeholder", respuesta["descripcion_ficha"]);
            $("#editGenero").val(respuesta["genero"]);


            // Aquí agregamos estado y condicion
            $("#editEstado").val(respuesta["estado"]);
            $("#editCondicion").val(respuesta["condicion"]);
        }
    });
});

// Limpiar inputs al cerrar modal (opcional)
$('#modalEditarUsuario').on('hidden.bs.modal', function() {
    $(this).find('form')[0].reset();
    // También puedes limpiar select2 u otros componentes si usas
    //recargar las vista de usuarios
    // location.reload();
    // Clear all input fields inside the modal
});

//tooltips
// Activar tooltips después de cada renderizado de tabla
$('#tblUsuarios').on('draw.dt', function () {
    $('[data-toggle="tooltip"]').tooltip();
});



//************************************************************
//
//  SCRIPT PARA IMPORTAR USUARIOS MASIVAMENTE
//
//************************************************************/
$(document).on("submit", "#modalImportarUsuarios form", function(e) {
    e.preventDefault();

    var fileInput = $("#archivoUsuarios");
    var file = fileInput[0].files[0];

    // Validar que se haya seleccionado un archivo
    if (!file) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Por favor, seleccione un archivo.'
        });
        return;
    }

    // Validar tipo de archivo (CSV o Excel)
    var validExtensions = ["csv", "xlsx", "xls"];
    var fileExtension = file.name.split('.').pop().toLowerCase();
    if ($.inArray(fileExtension, validExtensions) == -1) {
        Swal.fire({
            icon: 'error',
            title: 'Archivo no válido',
            text: 'Por favor, seleccione un archivo CSV o Excel (.csv, .xlsx, .xls).'
        });
        fileInput.val(''); // Limpiar el input de archivo
        return;
    }

    var formData = new FormData();
    formData.append("archivoUsuarios", file);
    formData.append("accion", "importarUsuariosMasivo"); // Acción para el controlador PHP

    Swal.fire({
        title: 'Importando usuarios...',
        text: 'Esto puede tardar un momento.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: "ajax/usuarios.ajax.php", // Ruta al controlador PHP
        method: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            try {
                var jsonResponse = JSON.parse(response);
                if (jsonResponse.status === "success") {
                    // Crear el archivo para descargar
                    if (jsonResponse.reporte) {
                        const contenidoBytes = Uint8Array.from(atob(jsonResponse.reporte), c => c.charCodeAt(0));
                        var blob = new Blob([contenidoBytes], {type: 'text/plain;charset=utf-8'});
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = jsonResponse.nombreArchivo;
                        
                        Swal.fire({
                            icon: 'success',
                            title: '¡Importación completada!',
                            text: jsonResponse.message,
                            showConfirmButton: true,
                            confirmButtonText: "Descargar reporte",
                            showCancelButton: true,
                            cancelButtonText: "Cerrar"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                link.click();
                            }
                            $("#modalImportarUsuarios").modal('hide');
                            $('#tblUsuarios').DataTable().ajax.reload();
                            fileInput.val(''); // Limpiar el input de archivo
                        });
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error en la importación',
                        text: jsonResponse.message || 'Ocurrió un error al importar los usuarios.'
                    });
                }
            } catch (e) {
                console.error("Raw server response:", response);
                console.error("Error parsing JSON:", e);
                Swal.fire({
                    icon: 'error',
                    title: 'Error inesperado',
                    text: 'La respuesta del servidor no es válida. Por favor, revise el archivo e inténtelo de nuevo.'
                });
                console.error("Error parsing response: ", response);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Error de conexión',
                text: 'No se pudo conectar con el servidor. Verifique su conexión e inténtelo de nuevo. Detalles: ' + textStatus + ' - ' + errorThrown
            });
            console.error("AJAX error: ", textStatus, errorThrown);
        }
    });
});