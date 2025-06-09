<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Salidas</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="tblSedes" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID Préstamo</th>
                                        <th>Usuario</th>
                                        <th>Tipo de Préstamo</th>
                                        <th>Estado De Préstamo</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $item = null;
                                    $valor = null;
                                    $salidas = Controladorsalidas::ctrMostrarsalidas($item, $valor);


                                    foreach ($salidas as $key => $value)   {
                                        echo '
                                        <tr> 
                                        
                                            <td>'.$value["id_prestamo"].'</td>
                                            <td>'.$value["nombre"].'</td>
                                            <td>'.$value["tipo_prestamo"].'</td>
                                            <td>'.$value["estado_prestamo"].'</td>
                                    
                                            <td>
                                                <div class="btn-group">
                                                    <button class="btn btn-info btn-sm btnVerDetalles" data-id="'.$value["id_prestamo"].'" data-toggle="modal" data-target="#modalDetallesPrestamo">
                                                        <i class="fa fa-eye"></i> Ver Detalles
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

    <!-- Modal de Detalles del Préstamo -->
<div class="modal fade" id="modalDetallesPrestamo" tabindex="-1" role="dialog" aria-labelledby="modalDetallesPrestamoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title">Detalle del Préstamo #<span id="numeroPrestamo"></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Usuario -->
                    <div class="col-md-4 text-center">
                        <img src="vistas/img/usuarios/default/anonymous.png" class="rounded-circle mb-2" alt="Avatar" width="100" height="100">
                        <h5 class="mb-0" id="detalleUsuarioNombre">Nombre Usuario</h5>
                        <small class="text-muted" id="detalleUsuarioRol">Rol</small>
                    </div>

                    <!-- Detalles del préstamo -->
                    <div class="col-md-8">
                        <div class="card card-outline card-info">
                            <div class="card-header py-2">
                                <h6 class="card-title m-0">Información del Préstamo</h6>
                            </div>
                            <div class="card-body p-2">
                                <div class="row">
                                    <div class="col-md-12">
                                        <p><strong>Estado:</strong> <span id="detalleTipoPrestamo">----</span></p>
                                        <p><strong>Fecha Préstamo::</strong> <span id="detalleFechaInicio">----</span></p>
                                        <p><strong>Fecha Devolución:</strong> <span id="detalleFechaFin">----</span></p>
                                        <p><strong>Motivo:</strong> <span id="detalleMotivoPrestamo">----</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Equipos Solicitados</h5>
                            </div>
                            <div class="card-body p-10">
                                <table class="table table-bordered table-striped " id="tblDetallePrestamo">
                                    <thead>
                                        <tr>
                                

                                            <th>ID</th>
                                            <th>Categoría</th>
                                            <th>Equipo</th>
                                            <th>etiqueta</th>
                                            <th>Serial</th>
                                            <th>Ubicación</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">

<button type="button" class="btn btn-success d-none" id="btnAceptarPrestamo">Autorizar Salida</button>

            </div>
        </div>
    </div>
</div>

