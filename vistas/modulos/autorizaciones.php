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
              // var_dump($prestamos[0]);
              foreach ($prestamos as $key => $value) {
                if ($value["tipo_prestamo"] == "Reservado") {
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
                    echo '<td class="bg-danger">' . $value["estado_prestamo"] . '</td>';
                  } else {
                    echo '<td>' . $value["estado_prestamo"] . '</td>';
                  };
                  echo   '<td>' . date('Y-m-d H:i', strtotime($value["fecha_solicitud"])) . '</td>
                          <td>
                            <div class="icheck-primary d-inline mx-1">';
                  if ($autorizaciones["firma_coordinacion"] == "Firmado") {
                    echo '<input type="checkbox" checked disabled>';
                  } else {
                    echo '<input type="checkbox" disabled>';
                  }
                  echo '</div>
                          </td>
                          <td>
                            <div class="icheck-primary d-inline mx-1">';
                  if ($autorizaciones["firma_lider_tic"] == "Firmado") {
                    echo '<input type="checkbox" checked disabled>';
                  } else {
                    echo '<input type="checkbox" disabled>';
                  }
                  echo '</div>
                          </td>
                          <td>
                            <div class="icheck-primary d-inline mx-1">';
                  if ($autorizaciones["firma_almacen"] == "Firmado") {
                    echo '<input type="checkbox" checked disabled>';
                  } else {
                    echo '<input type="checkbox" disabled>';
                  }
                  echo '</div>
                          </td>
                          <td>
                            <button class="btn btn-info btn-sm btnVerDetallePrestamo_Autorizar" idPrestamo="' . $value["id_prestamo"] . '" title="Detalles del Prestamo" data-toggle="modal" data-target="#modalVerDetallesPrestamo">
                              <i class="fas fa-eye"></i>
                            </button>
                          </td>
                        </tr>';
                } //fin REservado

              }
              ?>
            </tbody>
          </table>
        </div>
      </div>


    </section>
  </div>
</div>



<div class="modal fade" id="modalVerDetallesPrestamo">
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

<!-- creamos la modal del motivo de rechazo -->
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