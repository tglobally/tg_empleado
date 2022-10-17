<?php /** @var \tglobally\tg_empleado\controllers\controlador_em_empleado  $controlador */ ?>
<?php

use config\views;
$url_icons = (new views())->url_icons;
?>

<?php include 'templates/em_empleado/lista/secciones.php'; ?>

<div class="col-md-9 info-lista">
    <div class="col-lg-12 content">
        <h3 class="text-center titulo-form">Hola, <?php echo $controlador->datos_session_usuario['adm_usuario_user']; ?></h3>

        <div class="lista">
            <div class="card">

                <div class="card-body">
                    <div class="cont_tabla_sucursal  col-md-12">

                        <table class="table table-striped datatable">
                            <thead>
                            <tr>
                                <?php foreach ($controlador->datatable["titulos"] as $item) : ?>
                                    <th> <?php echo $item; ?> </th>
                                <?php endforeach;?>
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
</div>
