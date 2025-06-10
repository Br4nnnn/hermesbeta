<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Inicio</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    </ol>
                </div>
            </div>
        </div> <!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

         <div class="container-fluid">
              <!-- Info boxes -->
              <div class="row">

                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <?php
                    $conteos = ControladorSolicitudes::ctrContarEquiposPorCategoria();
                    ?>
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?php echo isset($conteos[1]) ? $conteos[1] : 0; ?></h3>

                            <p>Portátiles</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-desktop"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?php echo isset($conteos[2]) ? $conteos[2] : 0; ?></h3>

                            <p>Sonido</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-volume-up"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?php echo isset($conteos[3]) ? $conteos[3] : 0; ?></h3>

                            <p>Videobeam</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-video"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?php echo isset($conteos[5]) ? $conteos[5] : 0; ?></h3>

                            <p>Control remoto</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-gamepad"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
            <!-- row  -->

            <div class="row">
                <!-- Left side - Equipos -->
                <div class="col-md-7">
                    <div class="card">

                        <div class="card-header">
                            <h1 class="card-title">EQUIPOS</h1>
                        </div>

                        <div class="card-body p-10">

                            <!-- SOLICITANTE -->

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="input-group ">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            </div>
                                            <input type="text" class="form-control" name="NumeroIdSolicitante"
                                                id="NumeroIdSolicitante" placeholder="Número" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="input-group">
                                            <button class="btn btn-primary" id="btnBuscarSolicitante"><i
                                                    class="fas fa-search"></i></button>
                                        </div>

                                    </div>
                                </div>
                            </div>




                            <div class="infoEquiposSolicitados d-none">

                                <!-- RANGO DE FECHAS -->
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="far fa-calendar-alt"></i>
                                                    </span>
                                                </div>
                                                <input type="text" class="form-control float-right" id="reservation">
                                            </div>
                                            <!-- /.input group -->
                                        </div>
                                        <!-- /.form group -->
                                    </div>
                                </div>



                                <?php

                                //capturamos la fecha de hoy y la asignmaso a fechaInicio y fechaFin
                                $hoy = date("Y-m-d");
                                $respuesta = ControladorSolicitudes::ctrMostrarEquiposDisponible($hoy, $hoy);

                                ?>

                                <!-- TABLA DE EQUIPOS DISPONIBLES EN EL RANGO DE FECHAS SELECCIONADO  -->
                                <div class="row">
                                    <div class="col-lg-12">
                                        <table id="tblActivosSolicitar" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Descripción</th>
                                                    <th>Serial</th>
                                                    <th>Categoría</th>
                                                    <th>Ubicación</th>
                                                    <th>Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Loop through the equipos and display them in the table
                                                foreach ($respuesta as $key => $value) {
                                                    echo '<tr>
                                                        <td>' . $value["descripcion"] . '</td>
                                                        <td>' . $value["etiqueta"] . '</td>
                                                        <td>' . $value["categoria_nombre"] . '</td>
                                                        <td>' . $value["ubicacion_nombre"] . '</td>
                                                        <td> <button class="btn btn-primary btn-sm btnAgregarEquipo recoverButton" idEquipoAgregar="' . $value["equipo_id"] . '"><i class="fas fa-plus"></i> Agregar</button></td>
                                                    </tr>';
                                                }



                                                ?>




                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                            <!-- d-none -->

                        </div>
                        <!-- card.body  -->
                    </div>
                    <!-- card  -->
                </div>
                <!-- col-md-7  -->



                <!-- Right side - Solicitud -->
                <div class="col-md-5">
                    <div class="card">

                        <div class="card-header">
                            <h1 class="card-title">SOLICITUD</h1>
                        </div>


                        <div class="card-body">

                            <!-- =============================================

                                    FORMULARIO DE SOLICITUD 

                            ============================================= -->

                            <form action="" method="POST" id="idFormularioSolicitud">


                                <!-- NOMBRE SOLICITANTE -->
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="input-group ">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                </div>
                                                <input type="hidden" name="idSolicitante" id="idSolicitante">
                                                <input type="text" class="form-control" name="nombreSolicitante"
                                                    id="nombreSolicitante" placeholder="Nombre solicitante" readonly>
                                            </div>
                                            
                                            <div class="ficha-d d-none">
                                                <div class="input-group ">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                    </div>
                                                    <input type="hidden" name="idSolicitanteFicha" id="idSolicitanteFicha">
                                                    <input type="text" class="form-control" name="fichaSolicitante"
                                                        id="fichaSolicitante" placeholder="Ficha Solicitante" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="infoEquiposSolicitados d-none">

                                    <!-- RANGO DE FECHAS -->
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="input-group">
                                                    <input type="text" class="form-control float-right" id="initialDate"
                                                        name="fechaInicio" value="<?php echo $hoy; ?>">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="fas fa-arrow-right"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="far fa-calendar-alt"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" class="form-control float-right" id="finalDate"
                                                        name="fechaFin" value="<?php echo $hoy; ?>">
                                                </div>
                                                <!-- /.input group -->
                                            </div>
                                            <!-- /.form group -->
                                        </div>
                                    </div>


                                    <div class="form-group nuevoEquipo">
                                        <!-- //contenido dinamico desde js -->
                                    </div>



                                    <!-- OBSERVACIONES  -->
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label>Observaciones</label>
                                                <textarea class="form-control" id="motivoSolicitud" rows="3"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- d-none-->
                        </div>
                        <!-- card-body  -->

                        <div class="infoEquiposSolicitados d-none">

                            <div class="card-footer">

                                <div class="action-buttons">
                                    <button type="button" class="btn btn-danger btnCancelarSolicitud">Cancelar</button>
                                    <button type="submit" class="btn btn-primary btnGuardarSolicitud">Aceptar</button>
                                </div>
                            </div>
                        </div>
                        <!-- d-none-->




                        </form>




                    </div>

                    <div class="infoEquiposSolicitados d-none">

                        <div class="card">
                            <button type="button" class="btn btn-primary btn-block btnHistorial" id="btnHistorial">
                                <i class="fas fa-history"></i> Historial
                            </button>
                        </div>

                    </div>
                    <!-- d-none-->

                </div>
            </div>

            <!-- row  -->


        </div>
    </section>


</div>
<!-- /.content-wrapper -->