<?php /** @var \tglobally\tg_empleado\controllers\controlador_em_empleado $controlador */ ?>
<?php

use config\views;

$url_icons = (new views())->url_icons;
?>

<?php include 'templates/em_empleado/lista/secciones.php'; ?>

<div class="col-md-9 info-lista">
    <div class="col-lg-12 content">
        <h3 class="text-center titulo-form">
            Hola, <?php echo $controlador->datos_session_usuario['adm_usuario_user']; ?></h3>

        <div class="lista">
            <div class="card">

                <div class="card-body">
                    <div class="cont_tabla_sucursal  col-md-12">
                        <div class="botones" style="display: flex; justify-content: flex-end; align-items: center">
                            <form method="post" action="<?php echo $controlador->link_em_empleado_exportar; ?> "
                                  class="form-additional" id="form_export" style="width: 100%">
                                <?php echo $controlador->inputs->filtro_fecha_inicio; ?>
                                <?php echo $controlador->inputs->filtro_fecha_final; ?>
                            </form>
                            <button type="submit" class="btn btn-info" name="btn_action_next"
                                    style="border-radius: 5px" value="exportar" form="form_export">
                                Exportar
                            </button>
                        </div>
                        <table id="em_empleado" class="table table-striped "></table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
