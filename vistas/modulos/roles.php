<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Roles</h1>
                    </div>
                    <div class="col-sm-6">
                        <button class="btn btn-primary float-right" data-toggle="modal" data-target="#modalAddRol">Agregar rol</button>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

    <!-- Main content -->
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table id="tblRoles" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Rol</th>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Fecha de creación</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Fetch the list of roles
                                        $item = null;
                                        $valor = null;
                                        $roles = ControladorRoles::ctrMostrarRoles($item, $valor);
                                        // var_dump($roles); // Debugging line to check the data

                                        // Loop through the roles and display them in the table
                                        foreach ($roles as $key => $value) {
                                            echo '<tr>
                                                <td>' . ($key + 1) . '</td>
                                                <td>' . $value["nombre_rol"] . '</td>
                                                <td>' . $value["descripcion"] . '</td>
                                                <td>' . $value["fecha_creacion"] . '</td>
                                                <td>';
                                                if ($value["estado"] == "activo") {
                                                    echo '<button class="btn btn-success btn-xs btnActivarRol" idRol="'.$value["id_rol"].'" estadoRol="inactivo"">Activo</button>';
                                                } else {
                                                    echo '<button class="btn btn-danger btn-xs btnActivarRol" idRol="'.$value["id_rol"].'" estadoRol="activo">Inactivo</button>';
                                                };
                                                echo '</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button class="btn btn-default btn-xs btnEditarRol" idRol="'.$value["id_rol"].'" data-bs-toggle="tooltip" title= "Editar rol" "data-toggle="modal" data-target="#modalEditRol"><i class="fas fa-edit"></i></button>';
                                            echo '</div></td></tr>';
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
        <!-- /.content -->
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

    <!-- Modal for adding a new role -->
<div class="modal fade" id="modalAddRol" tabindex="-1" role="dialog" aria-labelledby="modalAddRolLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title" id="modalAddRolLabel">Agregar Rol</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="box-body">
                    <form id="formAddRol" method="POST"> 
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" id="nombreRol" name="nombreRol" required>
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea class="form-control" id="descripcionRol" name="descripcionRol" required></textarea>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>

                        <?php
                            // Include the PHP file for handling the form submission
                            $crearRol = new ControladorRoles();
                            $crearRol->ctrCrearRol();                            
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for editing a role -->
<div class="modal fade" id="modalEditRol" tabindex="-1" role="dialog" aria-labelledby="modalEditRolLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title" id="modalEditRolLabel">Editar Rol</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="box-body">
                    <form id="formEditRol" method="POST"> 
                        <input type="hidden" id="idEditRol" name="idEditRol" value="">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" id="nombreEditRol" name="nombreEditRol" required>
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea class="form-control" id="descripcionEditRol" name="descripcionEditRol" required></textarea>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Modificar</button>
                        </div>

                        <?php
                            // Include the PHP file for handling the form submission
                            $crearRol = new ControladorRoles();
                            $crearRol->ctrEditarRol();                            
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

       