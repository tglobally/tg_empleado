<?php /** @var \tglobally\tg_empleado\controllers\controlador_em_tipo_descuento $controlador */ ?>

<?php (new \tglobally\template_tg\template())->sidebar($controlador); ?>
<div class="col-md-9 formulario">
    <div class="col-lg-12">

        <h3 class="text-center titulo-form">Hola, <?php echo $controlador->datos_session_usuario['adm_usuario_user']; ?> </h3>

        <div class="  form-main" id="form">
            <form method="post" action="<?php echo $controlador->link_modifica_bd; ?>" class="form-additional">
                <?php echo $controlador->inputs->em_metodo_calculo_id; ?>
                <?php echo $controlador->inputs->monto; ?>
                <div class="buttons col-md-12">
                    <div class="col-md-6 btn-ancho">
                        <button type="submit" class="btn btn-info btn-guarda col-md-12 " >Modifica</button>
                    </div>
                    <div class="col-md-6 btn-ancho">
                        <a href="<?php echo $controlador->link_lista; ?>"  class="btn btn-info btn-guarda col-md-12 ">Lista</a>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

