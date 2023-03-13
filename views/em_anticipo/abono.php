<?php /** @var \tglobally\tg_empleado\controllers\controlador_em_anticipo $controlador */ ?>

<?php (new \tglobally\template_tg\template())->sidebar($controlador); ?>

<div class="col-md-9 formulario">
    <div class="col-lg-12">

        <h3 class="text-center titulo-form">
            Hola, <?php echo $controlador->datos_session_usuario['adm_usuario_user']; ?> </h3>

        <div class="  form-main" id="form">
            <form method="post" action="<?php echo $controlador->link_em_abono_anticipo_alta_bd; ?>"
                  class="form-additional">

                <?php echo $controlador->inputs->em_anticipo_id; ?>
                <?php echo $controlador->inputs->descripcion; ?>
                <?php echo $controlador->inputs->em_tipo_abono_anticipo_id; ?>
                <?php echo $controlador->inputs->cat_sat_forma_pago_id; ?>
                <?php echo $controlador->inputs->monto; ?>
                <?php echo $controlador->inputs->fecha; ?>

                <?php echo $controlador->inputs->hidden_row_id; ?>
                <?php echo $controlador->inputs->hidden_seccion_retorno; ?>
                <?php echo $controlador->inputs->hidden_id_retorno; ?>

                <div class="buttons col-md-12">
                    <div class="col-md-6 btn-ancho">
                        <button type="submit" class="btn btn-info btn-guarda col-md-12 " name="btn_action_next"
                                value="abono">Alta Abono
                        </button>
                    </div>
                    <div class="col-md-6 btn-ancho">
                        <a href="<?php echo $controlador->link_lista; ?>" class="btn btn-info btn-guarda col-md-12 ">Regresar</a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <div class="lista">
        <div class="card">
            <div class="card-header">
                <span class="text-header">Abonos</span>
            </div>
            <div class="card-body">
                <?php echo $controlador->contenido_table; ?>
            </div>
        </div>
    </div>

</div>

