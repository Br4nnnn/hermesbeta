<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="" class="brand-link">
    <img src="vistas/img/logo/logo_hermes.png" alt="Hermes Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">Hermes</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user (optional) -->
    <div class="user-panel mt-4 pb-3 mb-3 d-flex flex-column align-items-center">
      <div class="image mb-2">
        <?php
        // Obtener la foto del usuario desde el controlador
        $item = "id_usuario";
        $valor = $_SESSION["id_usuario"] ?? null;
        // Verificar si el id_usuario está definido en la sesión
        if ($valor) {
          $usuario = ControladorUsuarios::ctrMostrarUsuarios($item, $valor);
        } else {
          $usuario = null;
        }
        // Validar y asignar la ruta de la foto del usuario
        $fotoUsuario = isset($usuario["foto"]) && !empty($usuario["foto"])
          ? $usuario["foto"]
          : 'vistas/img/usuarios/default/anonymous.png';
        ?>
        <img src="<?php echo $fotoUsuario; ?>"
          class="img-circle elevation-2"
          alt="User Image"
          style="width: 45px; height: 45px; object-fit: cover; cursor: pointer; border: 2px solid #fff;"
          data-toggle="modal" data-target="#modalEditarPerfil">
      </div>
      <div class="info text-center">
        <a href="#" class="d-block font-weight-bold" style="font-size: 1.1rem;"><?php echo $_SESSION["nombre"] . " " . $_SESSION["apellido"] ?></a>
        <a href="#" class="d-block text-muted" style="font-size: 0.95rem;"><?php echo $_SESSION["nombre_rol"] ?></a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">
        <!-- Add icons to the links using the .nav-icon class
              with font-awesome or any other icon font library -->

        <!-- si el usuario tiene algun id_permiso entre 1 y 6 puede ver la opcion de administrar en el menu de lo contrario no -->




        <?php
        echo '<li class="nav-item">
            <a href="inicio" class="nav-link">
              <i class="nav-icon fas fa-home"></i>
              <p>
                Inicio
              </p>
            </a>
          </li>';

        if (ControladorValidacion::validarPermisoSesion([19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30])) {
          echo '<li class="nav-item">
                      <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-cogs"></i>
                      <p>
                        Administrar
                        <i class="right fas fa-angle-left"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview">';


          if (ControladorValidacion::validarPermisoSesion([22])) {
            echo '<li class="nav-item">
                        <a href="fichas" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Fichas</p>
                        </a>
                      </li>';
          }



          if (ControladorValidacion::validarPermisoSesion([23, 24, 25])) {
            echo '<li class="nav-item">
                        <a href="sedes" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Sedes</p>
                        </a>
                      </li>';
          }

          if (ControladorValidacion::validarPermisoSesion([26, 27, 28])) {
            echo '<li class="nav-item">
                        <a href="roles" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Roles</p>
                        </a>
                      </li>';
          }

          if (ControladorValidacion::validarPermisoSesion([30])) {
            echo '<li class="nav-item">
            <a href="permisos" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
              <p>Permisos</p>
            </a>
          </li>';

            echo '<li class="nav-item">
            <a href="auditoria" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
              <p>Auditoría</p>
            </a>
          </li>';
          }

          if (ControladorValidacion::validarPermisoSesion([29])) {
            echo '<li class="nav-item">
                        <a href="modulos" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Módulos</p>
                        </a>        
                      </li>';
          }

          echo '</ul>
                  </li>';
        }
        if (ControladorValidacion::validarPermisoSesion([19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30])) {
          echo '<li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Usuarios
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="usuarios" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Usuarios</p>
                </a>
              </li>
              </ul>';
        }
        if (ControladorValidacion::validarPermisoSesion([19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30])) {
          echo '
            <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-laptop"></i>
              <p>Equipos
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="inventario" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Inventario</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="reporte-equipos" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Reportes</p>
                </a>
              </li>
            </ul>';
        }


        echo '<li class="nav-item">
                <a href="#" class="nav-link">
                <i class="nav-icon fas fa-file-alt"></i>
                <p>
                Solicitudes
                <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">';
        // Solo administradores, líder TIC, mesa de ayuda y almacén 
        if (ControladorValidacion::validarPermisoSesion([19, 20, 21, 22])) {

        // "Solicitudes" y "Mis solicitudes" para todos los roles
        echo '<li class="nav-item">
              <a href="solicitudes" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
              <span class="badge badge-info right">6+</span>
              <p>
              Solicitudes
              </p>
              </a>
              </li>';
              
              echo '<li class="nav-item">
              <a href="consultar-solicitudes" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
              <span class="badge badge-info right">6+</span>
              <p>
              Consultar
              </p>
              </a>
              </li>';
        }
        echo '<li class="nav-item">
              <a href="mis-solicitudes" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
              <span class="badge badge-info right">6+</span>
              <p>
              Mis solicitudes
              </p>
              </a>
              </li>';
        echo '</ul>
            </li>';


        if (ControladorValidacion::validarPermisoSesion([10])) {
          echo '<li class="nav-item">
              <li class="nav-item">
                <a href="autorizaciones" class="nav-link">
                <i class="nav-icon fas fa-check"></i>
                <span class="badge badge-info right">4+</span>
                <p>Autorizaciones</p>
                </a>
              </li>
            </li>';
        }
        if (ControladorValidacion::validarPermisoSesion([19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30])) {
          echo '<li class="nav-item">
            <a href="salidas" class="nav-link">
              <i class="nav-icon fas fa-eye"></i>
              <span class="badge badge-info right">3+</span>
              <p>
              Salidas
              </p>
            </a>
            </li>';
        }

        if (ControladorValidacion::validarPermisoSesion([19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30])) {
          echo '<li class="nav-item">
            <a href="devoluciones" class="nav-link">
              <i class="nav-icon fas fa-reply"></i>
              <span class="badge badge-info right">6+</span>
              <p>
              Devoluciones
              </p>
            </a>
            </li>';
        }
  


        echo '<li class="nav-item">
            <a href="Mantenimiento" class="nav-link">
              <i class="nav-icon fas fa-tools"></i>
              <p>
                Mantenimiento
              </p>
            </a>
          </li>';
          
          

      

                
          ?>
          
        
          <li class="nav-item">
            <a href="reportes" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Reportes
              </p>
            </a>
          </li>

        </ul>
      </nav>
  </aside>



<!-- Modal para Editar Perfil -->
<div class="modal fade" id="modalEditarPerfil">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h4 class="modal-title">Editar Perfil</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php
        // Obtener información completa del usuario
        $item = "id_usuario";
        $valor = $_SESSION["id_usuario"];
        $usuario = ControladorUsuarios::ctrMostrarUsuarios($item, $valor);
        ?>
        <!-- Imagen de perfil con vista previa -->
        <div class="text-center mb-3">
          <!-- Vista previa (se actualiza con el js) -->
          <img id="vistaPreviaFoto"
            src="<?php echo $usuario['foto'] ?? 'vistas/img/usuarios/default/anonymous.png'; ?>"
            class="img-thumbnail"
            alt="Foto de Perfil"
            style="width: 100px; height: 100px; object-fit: cover;">
        </div>

        <form id="formEditarPerfil" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="idUsuario" value="<?php echo $_SESSION['id_usuario']; ?>">
          <input type="hidden" name="fotoActual" value="<?php echo $usuario['foto']; ?>">

           <!-- Nombre y Apellido (No editable) -->
          <div class="form-group">
            <div class="row">
              <div class="col-lg-6">
                <label>Nombre</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                  </div>
                  <input type="text" class="form-control" value="<?php echo $usuario['nombre']; ?>" name="editarNombre" readonly>
                </div>
              </div>
              <div class="col-lg-6">
                <label>Apellido</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                  </div>
                  <input type="text" class="form-control" value="<?php echo $usuario['apellido']; ?>" name="editarApellido" readonly>
                </div>
              </div>
            </div>
          </div>

          <!-- Campo para subir la foto de perfil -->
          <div class="form-group text-center foto-perfil-container">
              <label>Cambiar foto de perfil</label>
              <div>
                  <input type="file" id="fotoPerfil" name="editarFoto" accept="image/*" onchange="previewImage(event)">
                  <label for="fotoPerfil">
                      <span class="btn-foto-perfil">
                          <i class="fas fa-images"></i>
                      </span>
                  </label>
              </div>
              <small class="form-text text-muted">Haz clic en el ícono de galería para seleccionar una nueva foto (JPG, PNG, máx. 2MB).</small>
          </div>


           <!-- Información de Identificación (No editable) -->
          <div class="form-group">
            <div class="row">
              <div class="col-lg-6">
                <label>Tipo</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                  </div>
                  <input type="text" class="form-control" value="<?php echo $usuario['tipo_documento']; ?>" name="editarTipoDocumento" readonly>
                </div>
              </div>
              <div class="col-lg-6">
                <label>Número de documento</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                  </div>
                  <input type="text" class="form-control" value="<?php echo $usuario['numero_documento']; ?>" name="editarNumeroDocumento" readonly>
                </div>
              </div>
            </div>
          </div>

          <!-- Correo Electrónico (Editable) -->
          <div class="form-group">
            <label>Correo electrónico</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
              </div>
              <input type="email" class="form-control" name="editarEmail"
                value="<?php echo $usuario['correo_electronico']; ?>" required>
            </div>
          </div>

          <!-- Teléfono (Editable) -->
          <div class="form-group">
            <label>Teléfono</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-phone"></i></span>
              </div>
              <input type="tel" class="form-control" name="editarTelefono"
                value="<?php echo $usuario['telefono']; ?>" required>
            </div>
          </div>

          <!-- Dirección (Editable) -->
          <div class="form-group">
            <label>Dirección</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
              </div>
              <input type="text" class="form-control" name="editarDireccion"
                value="<?php echo $usuario['direccion']; ?>" required>
            </div>
          </div>

          <!-- Género (Editable con select) -->
          <div class="form-group">
            <label>Género</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-transgender"></i></span>
              </div>
              <select class="form-control" name="editarGenero">
                <option value="">Seleccione...</option>
                <option value="1" <?php echo $usuario['genero'] == '1' ? 'selected' : ''; ?>>Femenino</option>
                <option value="2" <?php echo $usuario['genero'] == '2' ? 'selected' : ''; ?>>Masculino</option>
                <option value="0" <?php echo $usuario['genero'] == '0' ? 'selected' : ''; ?>>No declara</option>
              </select>
            </div>
          </div>

          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary" name="editarPerfil">Guardar cambios</button>
          </div>

          <?php
          // Procesar la edición del perfil
          $editarPerfil = ControladorUsuarios::ctrEditarPerfil();
          ?>
        </form>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->