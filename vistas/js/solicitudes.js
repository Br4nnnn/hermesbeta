$(document).on("click", "#btnBuscarSolicitante", function () {
  idSolicitante = $("#NumeroIdSolicitante").val();
  // Validar que el campo no esté vacío
  if (idSolicitante === "") {
    alert("Por favor, ingrese un número de identificación.");
    return;
  } else {
    datos = new FormData();
    datos.append("idSolicitante", idSolicitante);
    $.ajax({
      url: "ajax/usuarios.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType: "json",
      success: function (respuesta) {
        console.log(respuesta);
        if (respuesta == "error") {

          alert("El solicitante no existe.");
        } else {
          $("#idSolicitante").val(respuesta["id_usuario"]);
          $("#nombreSolicitante").val(respuesta["nombre"] + " " + respuesta["apellido"] + " (" + respuesta["nombre_rol"] + ")");
          $("#nombreSolicitante").attr("disabled", true);

          $("#fichaSolicitante").val("Ficha: " + respuesta["codigo"]);
          $("#fichaSolicitante").attr("disabled", true);
          if (respuesta["nombre_rol"] == "Aprendiz") {
            if (respuesta["estado"] != "activo" || respuesta["estado_ficha"] != "activa" || respuesta["condicion"] == "penalizado") {

              if (respuesta["estado"] != "activo" && respuesta["condicion"] == "penalizado") {
                 $("#nombreSolicitante")
                  .removeClass("bg-success")
                  .addClass("bg-danger")
                  .val(respuesta["nombre"] + " " + respuesta["apellido"] + " (" + respuesta["nombre_rol"] + ") - "+  "" + respuesta["estado"] + " y " +  "" + respuesta["condicion"] + "");

              }
              else if (respuesta["estado"] != "activo") {
                $("#nombreSolicitante")
                  .removeClass("bg-success")
                  .addClass("bg-danger")
                  .val(respuesta["nombre"] + " " + respuesta["apellido"] + " (" + respuesta["nombre_rol"] + ") - "+  "" + respuesta["estado"] + " " );

              }
              else if (respuesta["condicion"] == "penalizado") {
                $("#nombreSolicitante")
                  .removeClass("bg-success")
                  .addClass("bg-danger")
                  .val(respuesta["nombre"] + " " + respuesta["apellido"] + " (" + respuesta["nombre_rol"] + ") - "+  "" + respuesta["condicion"] + " " );

              }


              // estado de la ficha
              if (respuesta["estado_ficha"] != "activa") {
                $("#fichaSolicitante")
                  .removeClass("bg-success")
                  .addClass("bg-danger")
                  .val("La ficha no está activa");
              } else {
                $("#fichaSolicitante")
                  .removeClass("bg-danger")
                  .addClass("bg-success");
              }


              // Ocultar la info si alguno está mal
              $(".infoEquiposSolicitados").addClass("d-none");
              $(".ficha-d").removeClass("d-none");


              return;
            }else{
              // ambos están activos
              $("#nombreSolicitante")
                .removeClass("bg-danger")
                .addClass("bg-success");

              $("#fichaSolicitante")
                .removeClass("bg-danger")
                .addClass("bg-success");
              $(".ficha-d").removeClass("d-none");
              $(".infoEquiposSolicitados").removeClass("d-none");
            }


            // initializeDataTable("#tblSolicitantes");
          } else {
            // Si no es aprendiz, no se valida el estado de la ficha
            $(".ficha-d").addClass("d-none");

            //validacion de otro rol, diferente al aprendiz
            if (respuesta["estado"] != "activo" && respuesta["condicion"] == "penalizado") {
                 $("#nombreSolicitante")
                  .removeClass("bg-success")
                  .addClass("bg-danger")
                  .val(respuesta["nombre"] + " " + respuesta["apellido"] + " (" + respuesta["nombre_rol"] + ") - "+  "" + respuesta["estado"] + " y " +  "" + respuesta["condicion"] + "");
                  $(".infoEquiposSolicitados").addClass("d-none");

              }
              else if (respuesta["estado"] != "activo") {
                $("#nombreSolicitante")
                  .removeClass("bg-success")
                  .addClass("bg-danger")
                  .val(respuesta["nombre"] + " " + respuesta["apellido"] + " (" + respuesta["nombre_rol"] + ") - "+  "" + respuesta["estado"] + " " );
                  $(".infoEquiposSolicitados").addClass("d-none");

              }
              else if (respuesta["condicion"] == "penalizado") {
                $("#nombreSolicitante")
                  .removeClass("bg-success")
                  .addClass("bg-danger")
                  .val(respuesta["nombre"] + " " + respuesta["apellido"] + " (" + respuesta["nombre_rol"] + ") - "+  "" + respuesta["condicion"] + " " );
                  $(".infoEquiposSolicitados").addClass("d-none");

              }else {
                  // si esta todo bien se ejecuta todo
                $("#nombreSolicitante")
                  .removeClass("bg-danger")
                  .addClass("bg-success")
                $(".infoEquiposSolicitados").removeClass("d-none");
              }

            //estado del usuario y condicion del usuario
            // if (respuesta["estado"] != "activo" || respuesta["condicion"] == "penalizado") {
            //   $("#nombreSolicitante")
            //     .removeClass("bg-success")
            //     .addClass("bg-danger")
            //     .val(respuesta["nombre"] + " " + respuesta["apellido"] + " (" + respuesta["nombre_rol"] + ") - Inactivo o Penalizado");

            //   $(".infoEquiposSolicitados").addClass("d-none");
            // } else {
            //   $("#nombreSolicitante").removeClass("bg-danger").addClass("bg-success");
            //   $(".infoEquiposSolicitados").removeClass("d-none");
            // }





          }
        }
      },
      error: function (xhr, status, error) {
        console.error("Error en la solicitud:", error);
        alert(
          "Ocurrió un error al buscar el solicitante. Por favor, inténtelo de nuevo."
        );
      },
    });
  }
}); // End of click event for #btnBuscarSolicitante

// validacion del rol, para que muestre en el input el numero de documento
$(document).ready(function () {
  console.log("usuarioActual:", usuarioActual);
  console.log("Permisos:", usuarioActual.permisos);

  if (usuarioActual.permisos.includes(31)) {
    // Tiene permiso 31, Campo editable
    $("#NumeroIdSolicitante")
      .val("") 
      .prop("readonly", false)
      .prop("disabled", false); // por si algún otro script lo desactivó
    
  } else {
    //  NO Tiene permiso 31, Pone la cedula por defecto
    $("#NumeroIdSolicitante")
      .val(usuarioActual.cedula)
      .prop("readonly", true);

    // Lanzar búsqueda automática del solicitante
    $("#btnBuscarSolicitante").trigger("click");
    
  }
});






$('#reservation').on('apply.daterangepicker', function (ev, picker) {
  var fechaInicio = picker.startDate.format('YYYY-MM-DD');
  var fechaFin = picker.endDate.format('YYYY-MM-DD');
  datos = new FormData();
  datos.append("fechaInicio", fechaInicio);
  datos.append("fechaFin", fechaFin);
  datos.append("accion", "mostrarEquipos");

  $.ajax({
    url: "ajax/solicitudes.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      console.log(respuesta);
      if (respuesta == "vacio") {
        alert("No se encontraron resultados.");
      } else {

        //  destruir la tabla si existe
        if ($.fn.DataTable.isDataTable('#tblActivosSolicitar')) {
          $('#tblActivosSolicitar').DataTable().clear().destroy();
        };
        // Inicializar el DataTable
        $('#tblActivosSolicitar').DataTable({
          "responsive": true,
          "autoWidth": false,
          "lengthChange": false,
          "info": true,
          "paging": true,
          "language": {
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "Selecciones un rango de fechas",
            "info": "Mostrando pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay equipos disponibles",
            "infoFiltered": "(filtrado de _MAX_ total registros)",
            "search": "Buscar:",
            "paginate": {
              "first":      "Primero",
              "last":       "Ultimo",
              "next":       "Siguiente",
              "previous":   "Anterior"
            }
          },
          "data": respuesta.map(function (item) {
            return [
              item.descripcion, // Reemplazar con el nombre real del campo para la descripción
              item.etiqueta, // Reemplazar con el nombre real del campo para la etiqueta
              item.categoria_nombre, // Reemplazar con el nombre real del campo para el nombre de la categoría
              item.ubicacion_nombre, // Reemplazar con el nombre real del campo para el nombre de la ubicación
              '<button class="btn btn-primary btn-sm btnAgregarEquipo recoverButton" idEquipoAgregar="' + item["equipo_id"] + '"><i class="fas fa-plus"></i> Agregar</button>' // Botón para agregar el activo
            ];
          })



        });

        // console.log("actualizar datatable con los resultados");
        // Actualizar los resultados obtenidos
        // $('#tblActivosSolicitar').DataTable().clear().rows.add(respuesta.map(function (item) {
        //   return [
        //     item.descripcion, // Reemplazar con el nombre real del campo para la descripción
        //     item.etiqueta, // Reemplazar con el nombre real del campo para la etiqueta
        //     item.categoria_nombre, // Reemplazar con el nombre real del campo para el nombre de la categoría
        //     item.ubicacion_nombre, // Reemplazar con el nombre real del campo para el nombre de la 
        //     '<button class="btn btn-primary btn-sm btnAgregarEquipo recoverButton" idEquipoAgregar="' + item["equipo_id"] + '"><i class="fas fa-plus"></i> Agregar</button>' // Botón para agregar el activo
        //   ];
        // })).draw();
        // Mostrar los resultados en el formulario 
        $("#initialDate").val(fechaInicio);
        $("#finalDate").val(fechaFin);

      }
    }

  });

});


$("#tblActivosSolicitar").on("click", ".btnAgregarEquipo", function () {
  var idEquipoAgregar = $(this).attr("idEquipoAgregar");
  console.log(idEquipoAgregar);
  $(this).removeClass("btn-primary").addClass("btn-success").html('<i class="fas fa-check"></i> Agregado').prop("disabled", true);
  var datos = new FormData();
  datos.append("accion", "traerEquipo");
  datos.append("idEquipoAgregar", idEquipoAgregar);

  $.ajax({
    url: "ajax/solicitudes.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      console.log(respuesta);

      $(".nuevoEquipo").append(
        '<div class="row">' +


        '<div class="col-lg-4">' +
        '<div class="input-group">' +
        '<div class="input-group-prepend">' +
        '<span class="input-group-text"><i class="fas fa-user"></i></span>' +
        '</div>' +
        '<input type="hidden" class="equipoIdSolicitado" name="' + respuesta["equipo_id"] + '" value="' + respuesta["equipo_id"] + '">' +
        '<input type="text" class="form-control" name="descripcion[]" placeholder="Descripción" value="' + respuesta["descripcion"] + '">' +
        '</div>' +
        '</div>' +

        '<div class="col-lg-3">' +
        '<div class="input-group">' +
        '<input type="text" class="form-control" name="serial[]" placeholder="Serial" value="' + respuesta["etiqueta"] + '">' +
        '</div>' +
        '</div>' +

        '<div class="col-lg-3">' +
        '<div class="input-group">' +
        '<input type="text" class="form-control" name="categoria[]" placeholder="Categoría" value="' + respuesta["categoria_nombre"] + '">' +
        '</div>' +
        '</div>' +

        '<div class="col-lg-2">' +
        '<div class="input-group">' +
        '<button type="button" class="btn btn-danger btnRemoverEquipo" idEquipoRemove="' + respuesta["equipo_id"] + '"><i class="fas fa-times"></i></button>' +
        '</div>' +
        '</div>' +

        '</div>'
      );
    }
  })
});


$("#tblActivosSolicitar").on("draw.dt", function () {



  if (localStorage.getItem("quitarEquipo") != null) {
    let listaIdEquipos = JSON.parse(localStorage.getItem("quitarEquipo"));
    let nuevaLista = [];
    for (let i = 0; i < listaIdEquipos.length; i++) {
      let id = listaIdEquipos[i]["idEquipo"];
      let $btn = $(".recoverButton[idEquipoAgregar='" + id + "']");

      //si hay un boton lo restauramos
      if ($btn.length > 0) {
        $btn.removeClass("btn-success").addClass("btn-primary").html('<i class="fas fa-plus"></i> Agregar').prop("disabled", false);
      } else {
        nuevaLista.push(listaIdEquipos[i]);
      }
    }
    if (nuevaLista.length > 0) {
      localStorage.setItem("quitarEquipo", JSON.stringify(nuevaLista));
    } else {
      localStorage.removeItem("quitarEquipo")
    }
  }

});


// REMOVER EQUIPO Y RESTAURAR BOTON
localStorage.removeItem("quitarEquipo");

$("#idFormularioSolicitud").on("click", ".btnRemoverEquipo", function () {

  var idEquipo = $(this).attr("idEquipoRemove");
  // $(this).parent().parent().parent().remove();
  $(this).closest(".row").remove();
  console.log("REmover ", idEquipo);

  let idQuitarEquipo = [];
  //guardamos en localstorage
  if (localStorage.getItem("quitarEquipo") != null) {
    idQuitarEquipo = JSON.parse(localStorage.getItem("quitarEquipo"))
  }

  idQuitarEquipo.push({ "idEquipo": idEquipo });
  localStorage.setItem("quitarEquipo", JSON.stringify(idQuitarEquipo));

  $(".recoverButton[idEquipoAgregar='" + idEquipo + "']").removeClass("btn-success").addClass("btn-primary").html('<i class="fas fa-plus"></i> Agregar').prop("disabled", false);

});

// *******************************************************************************************************************

$("#idFormularioSolicitud").on("submit", function (event) {

  event.preventDefault();
  //obtener datos del formulario
  let idSolicitante = $("#idSolicitante").val();
  let fechaInicio = $("#initialDate").val();
  let fechaFin = $("#finalDate").val();
  let motivo = $("#motivoSolicitud").val();

  let equipos = [];
  $(".equipoIdSolicitado").each(function () {
    equipos.push($(this).val());
  });

  //converitomos la lista de equipos en json
  equipos = JSON.stringify(equipos);


  //detenemos la ejecucion para debuguear
  // Debug: Stop execution here to inspect variables
  // debugger;


  let datos = new FormData();
  datos.append("idSolicitante", idSolicitante);
  datos.append("fechaInicio", fechaInicio);
  datos.append("fechaFin", fechaFin);
  datos.append("motivoSolicitud", motivo);
  datos.append("equipos", equipos);
  datos.append("accion", "guardarSolicitud");


  // Mostrar loading
  Swal.fire({
    title: 'Procesando solicitud',
    text: 'Por favor espere...',
    allowOutsideClick: false,
    allowEscapeKey: false,
    showConfirmButton: false,
    didOpen: () => {
      Swal.showLoading();
    }
  });



  $.ajax({
    url: "ajax/solicitudes.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      console.log("Respuesta recibida:", respuesta, "Tipo:", typeof respuesta);
      if (respuesta == "ok") {
        Swal.fire({
          icon: "success",
          title: "Solicitud enviada",
          text: "La solicitud se ha enviado correctamente",
          showConfirmButton: false,
          timer: 1500,
          timerProgressBar: true,
          willClose: () => {
            window.location = "solicitudes";
          }
        });
      } else {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "Ha ocurrido un error al enviar la solicitud",
          showConfirmButton: false,
          timer: 1500,
          timerProgressBar: true,
        })
      }
    }
  });
});
//##*****Historial de solicitudes
$(document).on("click", ".btnHistorial", function () {
  let cedula = $("#NumeroIdSolicitante").val();

  if (cedula) {
    window.location.href = "index.php?ruta=consultar-solicitudes&cedula=" + encodeURIComponent(cedula) + "&origin=solicitudes&autoBuscar=1";
  } else {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'No hay un solicitante seleccionado'
    });
  }
});
// ---------------------//
// Consultar solicitudes//
// ---------------------//
$(document).on("click", "#btnHistorialSolicitud", function () {
  let numeroDocumento = $("#NumeroIdSolicitante").val();

  if (numeroDocumento != "") {
    window.location.href = "index.php?ruta=consultar-solicitudes&documento=" + numeroDocumento;
  } else {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Debe seleccionar un solicitante primero'
    });
  }
});