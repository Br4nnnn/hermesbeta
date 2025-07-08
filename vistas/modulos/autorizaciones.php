<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Autorizaciones</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Préstamos Reservados</h3>
          <button type="button" class="btn btn-info float-right" id="btnVerAutorizados" data-toggle="modal" data-target="#modalPrestamosAutorizados">
            Ver Préstamos Autorizados
          </button>
        </div>
        <div class="card-body">
          <table id="tblAutorizaciones" class="table table-bordered table-striped">
            <thead class="bg-dark">
              <tr>
                <th>Préstamo</th>
                <th>Solicitante</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th>Estado Préstamo</th>
                <th>Fecha Solicitud</th>
                <th>Coo</th>
                <th>Tic</th>
                <th>Alm</th>
                <th>Detalle</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $item = null;
              $valor = null;
              $prestamos = ControladorSolicitudes::ctrMostrarPrestamo($item, $valor);

              foreach ($prestamos as $key => $value) {
                if ($value["tipo_prestamo"] == "Reservado" && $value["estado_prestamo"] != "Prestado" && $value["estado_prestamo"] != "Devuelto") {
                  $item = "id_prestamo";
                  $valor = $value["id_prestamo"];
                  $autorizaciones = ControladorAutorizaciones::ctrMostrarAutorizaciones($item, $valor);
                  // var_dump($autorizaciones);

                  echo '<tr>
                          <td>' . $value["id_prestamo"] . '</td>
                          <td>' . $value["solicitante"] . '</td>
                          <td>' . date('Y-m-d H:i', strtotime($value["fecha_inicio"])) . '</td>
                          <td>' . date('Y-m-d H:i', strtotime($value["fecha_fin"])) . '</td>';
                  if ($value["estado_prestamo"] == "Rechazado") {
                    echo '<td> <p class="text-danger" title="Rechazado por ' . $autorizaciones["usuario_que_rechazo"] . '"> ' . $value["estado_prestamo"] . '</p></td>';
                  } else {
                    echo '<td>' . $value["estado_prestamo"] . '</td>';
                  };
                  echo   '<td>' . date('Y-m-d H:i', strtotime($value["fecha_solicitud"])) . '</td>
                          <td>
                            <div class="icheck-primary d-inline mx-1">';
                  if ($autorizaciones["firma_coordinacion"] == "Firmado") {
                    echo '<input type="checkbox" checked disabled title="Aprobado por ' . $autorizaciones["nombre_usuario_coordinacion"] . '">';
                  } else {
                    echo '<input type="checkbox" disabled title="En trámite...">';
                  }
                  echo '</div>
                          </td>
                          <td>
                            <div class="icheck-primary d-inline mx-1">';
                  if ($autorizaciones["firma_lider_tic"] == "Firmado") {
                    echo '<input type="checkbox" checked disabled title="Aprobado por ' . $autorizaciones["nombre_usuario_lider_tic"] . '">';
                  } else {
                    echo '<input type="checkbox" disabled title="En trámite...">';
                  }
                  echo '</div>
                          </td>
                          <td>
                            <div class="icheck-primary d-inline mx-1">';
                  if ($autorizaciones["firma_almacen"] == "Firmado") {
                    echo '<input type="checkbox" checked disabled title="Aprobado por ' . $autorizaciones["nombre_usuario_almacen"] . '">';
                  } else {
                    echo '<input type="checkbox" disabled title="En trámite...">';
                  }
                  echo '</div>
                          </td>
                          <td>
                            <div class="btn-group">
                              <button title="Consultar detalles de préstamo" class="btn btn-default btnVerDetallePrestamo_Autorizar" idPrestamo="' . $value["id_prestamo"] . '" data-toggle="modal" data-target="#modalVerDetallesPrestamo">
                                <i class="fas fa-eye"></i>
                              </button>   
                            </div>
                          </td>
                        </tr>';
                }
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </div>
</div>

<!-- Modal para ver detalles del préstamo -->
<div class="modal fade" id="modalVerDetallesPrestamo" tabindex="-1" role="dialog" aria-labelledby="modalVerUsuarioLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">

      <div class="modal-header bg-info">
        <h5 class="modal-title">Detalle del Préstamo #<span id="numeroPrestamo"></span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">

        <div class="row">

          <!-- Información del Usuario -->
          <div class="col-md-3 text-center">
            <div class="user-avatar">
              <img class="img-circle elevation-2 mb-3" id="imgUsuario" src="vistas/img/usuarios/default/anonymous.png" alt="User Image" style="width: 120px; height: 120px;">
            </div>
            <h5 class="mb-1" id="usuarioNombre">Nombre del solicitante</h5>
            <p class="text-muted" id="userRol">Aprendiz</p>
          </div>

          <!-- Informacion de usuario y prestamo -->
          <div class="col-md-9 col-sm-12">
            <div class="row">

              <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="info-box">
                  <span class="info-box-icon bg-info"><i class="fas fa-id-card"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">Identificación</span>
                    <span class="info-box-number" id="usuarioIdentificacion">Identificación</span>
                  </div>
                </div>
              </div>

              <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="info-box">
                  <span class="info-box-icon bg-info"><i class="fas fa-phone"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">Teléfono</span>
                    <span class="info-box-number" id="usuarioTelefono">000000</span>
                  </div>
                </div>
              </div>

              <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="info-box">
                  <span class="info-box-icon bg-info"><i class="fas fa-graduation-cap"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">Ficha</span>
                    <span class="info-box-number" id="usuarioFicha">2847523</span>
                  </div>
                </div>
              </div>

              <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="info-box">
                  <span class="info-box-icon bg-info"><i class="fas fa-comment"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">Motivo</span>
                    <span class="info-box-number" id="detalleMotivoPrestamo">aaaa</span>
                  </div>
                </div>
              </div>

              <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="info-box">
                  <span class="info-box-icon bg-info"><i class="fas fa-calendar-alt"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">Fecha Inicio</span>
                    <span class="info-box-number" id="detalleFechaInicio">2025-06-27</span>
                  </div>
                </div>
              </div>

              <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="info-box">
                  <span class="info-box-icon bg-info"><i class="fas fa-calendar-check"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">Fecha Devolución</span>
                    <span class="info-box-number" id="detalleFechaFin">2025-07-05</span>
                  </div>
                </div>
              </div>

            </div>
            <!-- row  -->

            <!-- Estado -->
            <div class="row">
              <div class="col-12">
                <div class="callout callout-success">
                  <h5><i class="fas fa-check"></i> Estado:</h5>
                  <span class="badge badge-success badge-lg" id="estadoPrestamo">Autorizado</span>
                </div>
              </div>
            </div>

          </div>
          <!-- Cierra informacion de usuario y prestamo -->

        </div>
        <!-- row  -->



        <!-- Detalles de los equipos -->

        <div class="card">
          <div class="card-header">
            <h5 class="card-title">Equipos Solicitados</h5>
          </div>
          <div class="card-body">
            <table class="table table-bordered table-striped" id="tblDetallePrestamo" style="width:100%">
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
      <!-- modal body -->





      <div class="modal-footer">
        <input type="hidden" id="idRolSesion" value="<?php echo $_SESSION['rol'] ?>">
        <input type="hidden" id="nombre_rolSesion" value="<?php echo $_SESSION['nombre_rol'] ?>">
        <input type="hidden" id="id_UsuarioSesion" value="<?php echo $_SESSION['id_usuario'] ?>">

        <div class="alert alert-danger d-none" id="alertaRechazado" role="alert">El préstamo fue rechazado por otro usuario - <span id="usuarioNombreRechaza"></span></div>

        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>

        <button type="button" class="btn btn-danger btnRechazar btnAccionFirma d-none" data-toggle="modal" data-target="#modalMotivoRechazo">Rechazar</button>
        <button type="button" class="btn btn-primary btnAutorizar btnAccionFirma d-none">Autorizar</button>
        <button type="button" class="btn btn-danger btnDesautorizar d-none">Desautorizar</button>
      </div>

    </div>
  </div>
</div>


  <!-- Modal para motivo de rechazo -->
  <div class="modal fade" id="modalMotivoRechazo">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-danger">
          <h4 class="modal-title">Motivo de Rechazo</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="" method="post">
          <input type="hidden" id="numeroPrestamoRechazar" name="numeroPrestamoRechazar">
          <input type="hidden" id="idUsuarioRechazar" name="idUsuarioRechazar" value="<?php echo $_SESSION['id_usuario'] ?>">
          <input type="hidden" id="idRolRechazar" name="idRolRechazar" value="<?php echo $_SESSION['rol'] ?>">
          <div class="modal-body">
            <div class="form-group">
              <label for="motivoRechazo">Motivo de Rechazo:</label>
              <textarea class="form-control" id="motivoRechazo" rows="4" name="motivoRechazoAutorizacion" required></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-danger btnRechazarConfirmar">Rechazar</button>
          </div>
          <?php
          $rechazar = new ControladorAutorizaciones();
          $rechazar->ctrRechazarPrestamo();
          ?>
        </form>
      </div>
    </div>
  </div>