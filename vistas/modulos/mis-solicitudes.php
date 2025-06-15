<!-- Contenido Principal -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <h1>Consultar Préstamos</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">


                <!-- Tabla de Préstamos -->

                <div class="card card-success col-lg-12" id="resultados">
                    <div class="card-header">
                        <h3 class="card-title">Préstamos del Usuario</h3>
                    </div>
                    <div class="card-body">

                        <table class="table table-bordered table-striped" id="tblMisPrestamosUsuario">
                            <thead class="thead-dark">
                                <tr>
                                    <th># </th>
                                    <th>Préstamo</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Fin</th>
                                    <th>Estado</th>
                                    <th>Motivo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                $item = "usuario_id";
                                $valor = $_SESSION['id_usuario'];
                                $prestamos = ControladorSolicitudes::ctrMostrarSolicitudes($item, $valor);
                                // var_dump($prestamos);
                                
                                // Verificamos si es un array multidimensional
                                if(is_array($prestamos) && isset($prestamos[0]) && is_array($prestamos[0])) {
                                    // Es un array de múltiples registros
                                    foreach($prestamos as $prestamo) {
                                        echo '<tr>
                                                <td>' . $prestamo["id_prestamo"] . '</td>
                                                <td>' . $prestamo["tipo_prestamo"] . '</td>
                                                <td>' . $prestamo["fecha_inicio"] . '</td>
                                                <td>' . $prestamo["fecha_fin"]. '</td>
                                                <td>' . $prestamo["estado_prestamo"]. '</td>
                                                <td>' . $prestamo["motivo"]. '</td>
                                                <td>
                                                    <button class="btn btn-primary btn-sm btnVerDetalle" idPrestamo="'. $prestamo["id_prestamo"]. ' " title="Detalles del prestamo"data-toggle="modal" data-target="#modalMisDetalles"><i class="fa fa-eye"></i></button>
                                                </td>
                                            </tr>';
                                    }
                                } else if(is_array($prestamos) && isset($prestamos[0])) {
                                    // Es un solo registro
                                    echo '<tr>
                                            <td>' . $prestamos["id_prestamo"] . '</td>
                                            <td>' . $prestamos["tipo_prestamo"] . '</td>
                                            <td>' . $prestamos["fecha_inicio"] . '</td>
                                            <td>' . $prestamos["fecha_fin"]. '</td>
                                            <td>' . $prestamos["estado_prestamo"]. '</td>
                                            <td>' . $prestamos["motivo"]. '</td>
                                            <td>
                                                <button class="btn btn-primary btn-sm btnVerMisDetalles" idPrestamo="'. $prestamos["id_prestamo"]. '" title="Detalles del prestamo  " data-toggle="modal" data-target="#modalMisDetalles"><i class="fa fa-eye"></i></button>
                                            </td>
                                        </tr>';
                                }else {
                                    // No hay registros
                                    echo '<tr>
                                            <td colspan="7">No hay registros</td>
                                        </tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        </div>
    </section>
</div>

<!-- Modal Detalle Préstamo -->
<div class="modal fade" id="modalMisDetalles">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title">Detalle del Préstamo #<span id="num eroPrestamo"></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <dl class="row">

                            <dt class="col-sm-4">Estado:</dt>
                            <dd class="col-sm-8" id="detalleTipoPrestamo"></dd>

                            <dt class="col-sm-4">Fecha Préstamo:</dt>
                            <dd class="col-sm-8" id="detalleFechaInicio"></dd>

                            <dt class="col-sm-4">Fecha Devolución:</dt>
                            <dd class="col-sm-8" id="detalleFechaFin"></dd>

                            <dt class="col-sm-4">Motivo:</dt>
                            <dd class="col-sm-8" id="detalleMotivoPrestamo"></dd>
                        </dl>
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
                                        
                                        <!-- Aquí se cargarán los detalles del préstamo -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>