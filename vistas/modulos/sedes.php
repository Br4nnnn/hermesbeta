<?php
$item = "id_modulo";
$valor = 8;
$respuesta = ControladorModulos::ctrMostrarModulos($item, $valor);
if ($respuesta["estado"] == "inactivo") {
    echo '<script>
                window.location = "desactivado";
            </script>';
}

?>



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Sedes</h1>
                </div>
                <div class="col-sm-6">
                    <?php
                    // Check if the user has permission to add a new Sede
                    if (ControladorValidacion::validarPermisoSesion([24])) {
                        echo '<button class="btn btn-primary float-right" data-toggle="modal" data-target="#modalAddSede">Agregar Sede</button>';
                    }

                    ?>
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
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Dirección</th>
                                        <th>Descripción</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    // Fetch the list of Sedes
                                    $item = null;
                                    $valor = null;
                                    $sedes = ControladorSedes::ctrMostrarSedes($item, $valor);
                                    // var_dump($sedes); // Debugging line to check the data

                                    // Loop through the Sedes and display them in the table
                                    foreach ($sedes as $key => $value) {
                                        echo '<tr>
                                                <td>' . ($key + 1) . '</td>
                                                <td>' . $value["nombre_sede"] . '</td>
                                                <td>' . $value["direccion"] . '</td>
                                                <td>' . $value["descripcion"] . '</td>
                                                <td>';
                                        if (ControladorValidacion::validarPermisoSesion([23])) {
                                            if ($value["estado"] == "activa") {
                                                echo '<button class="btn btn-success btn-xs btnActivarSede" idSede="' . $value["id_sede"] . '" estadoSede="inactiva"">Activa</button>';
                                            } else {
                                                echo '<button class="btn btn-danger btn-xs btnActivarSede" idSede="' . $value["id_sede"] . '" estadoSede="activa">Inactiva</button>';
                                            };
                                        } else {
                                            if ($value["estado"] == "activa") {
                                                echo '<button class="btn btn-success btn-xs disabled">Activa</button>';
                                            } else {
                                                echo '<button class="btn btn-danger btn-xs disabled">Inactiva</button>';
                                            };
                                        }
                                        echo '</td>
                                                <td>';
                                        if (ControladorValidacion::validarPermisoSesion([23])) {
                                            echo '<div class="btn-group">
                                                        <button class="btn btn-default btn-xs btnEditarSede" idSede="' . $value["id_sede"] . '" data-bs-toggle="tooltip" title="Editar sede" data-toggle="modal" data-target="#modalEditSede"><i class="fas fa-edit"></i></button>
                                                    </div>';
                                        } else {
                                            echo '<div class="btn-group">
                                                        <button class="btn btn-default disabled btn-xs"><i class="fas fa-edit"></i></button>
                                                    </div>';
                                        }
                                        echo '</td>
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
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Modal Add Sede -->
<div class="modal fade" id="modalAddSede" tabindex="-1" role="dialog" aria-labelledby="modalAddSedeLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title" id="modalAddSedeLabel">Agregar Nueva Sede</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="box-body">
                    <form id="formAddSede" method="POST">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" id="nombreSede" name="nombreSede" required>
                        </div>
                        <div class="form-group">
                            <label for="direccion">Dirección</label>
                            <input type="text" class="form-control" id="direccionSede" name="direccionSede" required>
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea class="form-control" id="descripcionSede" name="descripcionSede" required></textarea>
                        </div>

                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>

                        <?php
                        // Include the PHP file for handling the form submission
                        $crearSede = new ControladorSedes();
                        $crearSede->ctrCrearSede();
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal Edit Sede -->
<div class="modal fade" id="modalEditSede" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title" id="modalEditSedeLabel">Editar Sede</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="box-body">
                    <form id="formEditSede" method="POST">
                        <input type="hidden" id="idEditSede" name="idEditSede">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" id="nombreEditSede" name="nombreEditSede" required>
                        </div>
                        <div class="form-group">
                            <label for="direccion">Dirección</label>
                            <input type="text" class="form-control" id="direccionEditSede" name="direccionEditSede" required>
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea class="form-control" id="descripcionEditSede" name="descripcionEditSede" required></textarea>
                        </div>

                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Editar</button>
                        </div>

                        <?php
                        $crearSede = new ControladorSedes();
                        $crearSede->ctrEditarSede();
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>