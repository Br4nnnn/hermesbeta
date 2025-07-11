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
                <div class="col-md-4">

                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Buscar Usuario</h3>
                        </div>
                        <div class="card-body">
                            <div class="input-group">
                                <input type="text" id="cedulaUsuario" class="form-control"
                                    placeholder="Ingrese número de cédula">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" id="btnBuscarUsuarioConsultar">
                                        <i class="fas fa-search"></i> Buscar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Information Card -->
                    <div class="card card-info d-none" id="userinfo">
                        <div class="card-header">
                            <h3 class="card-title">Información del Usuario</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <dl class="row">
                                        <input type="hidden" id="userId">
                                        <dt class="col-sm-4">Nombres:</dt>
                                        <dd class="col-sm-8" id="userNames">-</dd>

                                        <dt class="col-sm-4">Apellidos:</dt>
                                        <dd class="col-sm-8" id="userLastName">-</dd>
                                    </dl>
                                </div>
                                <div class="col-md-12">
                                    <dl class="row">
                                        <dt class="col-sm-4">Dirección:</dt>
                                        <dd class="col-sm-8" id="userAddress">-</dd>

                                        <dt class="col-sm-4">Teléfono:</dt>
                                        <dd class="col-sm-8" id="userPhone">-</dd>
                                    </dl>
                                </div>
                                <div class="col-md-12">
                                    <dl class="row">
                                        <dt class="col-sm-4">Correo:</dt>
                                        <dd class="col-sm-8" id="userEmail">-</dd>

                                        <dt class="col-sm-4">Rol:</dt>
                                        <dd class="col-sm-8" id="userRole">-</dd>
                                    </dl>
                                </div>

                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary btn-block d-none" id="btnVolverSolicitudes">
                        <i class="fas fa-arrow-left"></i> Volver
                    </button>
                    <!-- <button class="btn btn-secondary" id="btnVolverSolicitudes">
                    <i class="fas fa-arrow-left"></i> Volver
                    </button> -->
                </div>

                <!-- Tabla de Préstamos -->

                <div class="card card-success col-md-8" id="resultados" style="display: none;">
                    <div class="card-header">
                        <h3 class="card-title">Préstamos del Usuario</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped" id="tblPrestamosUsuario">
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
                                <!-- Aquí se cargarán los datos de los préstamos dinamicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        </div>
    </section>
</div>

<!-- Modal Detalle Préstamo -->
<div class="modal fade" id="modal-detalle">
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
                                <div class="table-responsive">
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>