window.addEventListener('load', function() {
  initializeDataTable("#tblSedes");
  //initializeDataTable("#tblFichas");
  // initializeDataTable("#tblUsuarios");
  // initializeDataTable("#tblEquipos");
  initializeDataTable("#tblRoles");
  initializeDataTable("#tblAutorizaciones");
  initializeDataTable("#tblDevoluciones");
  initializeDataTable("#tblMantenimiento");
  initializeDataTable("#tblModalHistoricoSolicitudes");
  initializeDataTable("#tblMisPrestamosUsuario");
  // initializeDataTable("#tblPermisos");
});

window.addEventListener('load', function() {
  initializeDataTableSimple("#tblPerGestionEquipos");
  initializeDataTableSimple("#tblPerSolicitudesAutorizaciones");
  initializeDataTableSimple("#tblActivosSolicitar");
});

function initializeDataTable(selector) {
  $(selector).DataTable({
    "responsive": true,
    "autoWidth": false,
    "lengthChange": true,
    "lengthMenu": [10, 25, 50, 100],
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
      }
    },   
    "buttons": ["csv", "excel", "pdf"]
  }).buttons().container().appendTo(selector + '_wrapper .col-md-6:eq(0)');
}


function initializeDataTableSimple(selector) {
  $(selector).DataTable({
    "responsive": true, 
    "ordering": false,
    "searching": true,
    "autoWidth": false,
    "lengthChange": false,
    "paging": true,
    "info": false,
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
      }
    },   
  });
}

var Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 2000
});

$('#reservation').daterangepicker();


