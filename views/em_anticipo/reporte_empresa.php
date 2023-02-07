<?php /** @var \tglobally\tg_empleado\controllers\controlador_em_empleado $controlador */ ?>
<?php (new \tglobally\template_tg\template())->sidebar($controlador); ?>
<div class="col-md-9 formulario">
    <div class="col-lg-12">

        <h3 class="text-center titulo-form">Hola, <?php echo $controlador->datos_session_usuario['adm_usuario_user']; ?> </h3>

        <div class="  form-main" id="form">
            <form method="post" action="./index.php?seccion=em_anticipo&accion=reporte_empresa&session_id=<?php echo $controlador->session_id; ?>&registro_id=<?php echo $controlador->registro_id; ?>" class="form-additional" enctype="multipart/form-data">

                <?php echo $controlador->inputs->org_sucursal_id; ?>
                <?php echo $controlador->inputs->em_tipo_anticipo_id; ?>
                <?php echo $controlador->inputs->codigo; ?>
                <?php echo $controlador->inputs->fecha_inicio; ?>
                <?php echo $controlador->inputs->fecha_final; ?>

                <div class="botones">
                    <div class="controls">
                        <button type="submit" class="btn btn-success export" name="btn_action_next"
                                style="border-radius: 5px" value="exportar" form="form_export">
                            Exportar
                        </button>                            </div>
                </div>
            </form>
            <table id="em_anticipo" class="datatables table table-striped "></table>
        </div>
    </div>
</div>
