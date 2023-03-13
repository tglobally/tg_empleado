<?php /** @var \tglobally\tg_empleado\controllers\controlador_em_empleado $controlador */ ?>
<?php (new \tglobally\template_tg\template())->sidebar($controlador); ?>
<div class="col-md-9 formulario">
    <div class="col-lg-12">

        <h3 class="text-center titulo-form">Hola, <?php echo $controlador->datos_session_usuario['adm_usuario_user']; ?> </h3>

        <div class="  form-main" id="form">
            <form method="post" action="<?php echo $controlador->link_alta_bd; ?>" class="form-additional">
                <?php echo $controlador->inputs->em_empleado_id; ?>
                <?php echo $controlador->inputs->em_tipo_anticipo_id; ?>
                <?php echo $controlador->inputs->em_tipo_descuento_id; ?>
                <?php echo $controlador->inputs->descripcion; ?>
                <?php echo $controlador->inputs->monto; ?>
                <?php echo $controlador->inputs->n_pagos; ?>
                <?php echo $controlador->inputs->fecha_prestacion; ?>
                <?php echo $controlador->inputs->fecha_inicio_descuento; ?>
                <?php echo $controlador->inputs->comentarios; ?>
                <div class="buttons col-md-12">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-info btn-guarda col-md-12 " name="btn_action_next" value="modifica">Guarda</button>
                    </div>
                    <div class="col-md-6 ">
                        <a href="<?php echo $controlador->link_lista; ?>"  class="btn btn-info btn-guarda col-md-12 ">Regresar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
