    <?php
        $item = "id_modulo";
        $valor = 10;
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
                    <h1>Módulos</h1>
                </div>
                <!-- <div class="col-sm-6">
                    <button class="btn btn-primary float-right" data-toggle="modal" data-target="#modalAddModulo">Agregar Módulo</button>
                </div> -->
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
                            <table id="tblModulos" class="table table-bordered table-striped">
                                <thead class="bg-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Fetch the list of Modulos
                                    $item = null;
                                    $valor = null;
                                    $modulos = ControladorModulos::ctrMostrarModulos($item, $valor);

                                    // Loop through the Modulos and display them in the table
                                    foreach ($modulos as $key => $value) {
                                        echo '<tr>
                                            <td>' . ($key + 1) . '</td>
                                            <td>' . $value["nombre"] . '</td>
                                            <td>' . $value["descripcion"] . '</td>
                                            <td>';
                                            if ($value["estado"] == "activo") {
                                                echo '<button class="btn btn-success btn-xs btnActivarModulo" idModulo="'.$value["id_modulo"].'" estadoModulo="inactivo">Activo</button>';
                                            } else {
                                                echo '<button class="btn btn-danger btn-xs btnActivarModulo" idModulo="'.$value["id_modulo"].'" estadoModulo="activo">Inactivo</button>';
                                            }
                                            echo '</tr>';
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
