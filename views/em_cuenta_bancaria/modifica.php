<?php /** @var \tglobally\tg_empleado\controllers\controlador_em_cuenta_bancaria $controlador */ ?>

<?php (new \tglobally\template_tg\template())->sidebar($controlador); ?>
<div class="col-md-9 formulario">
    <div class="col-lg-12">

        <h3 class="text-center titulo-form">Hola, <?php echo $controlador->datos_session_usuario['adm_usuario_user']; ?> </h3>

        <div class="  form-main" id="form">
            <form method="post" action="./index.php?seccion=em_cuenta_bancaria&accion=modifica_bd&session_id=<?php echo $controlador->session_id; ?>&registro_id=<?php echo $controlador->registro_id; ?>" class="form-additional">
                <?php echo $controlador->inputs->codigo; ?>
                <?php echo $controlador->inputs->codigo_bis; ?>
                <?php echo $controlador->inputs->descripcion; ?>
                <?php echo $controlador->inputs->num_cuenta; ?>
                <?php echo $controlador->inputs->clabe; ?>
                <?php echo $controlador->inputs->em_empleado_id; ?>
                <?php echo $controlador->inputs->bn_sucursal_id; ?>
                <div class="buttons col-md-12">
                    <div class="col-md-6 btn-ancho">
                        <button type="submit" class="btn btn-info btn-guarda col-md-12 " >Modifica</button>
                    </div>
                    <div class="col-md-6 btn-ancho">
                        <a href="index.php?seccion=em_cuenta_bancaria&accion=lista&session_id=<?php echo $controlador->session_id; ?>"  class="btn btn-info btn-guarda col-md-12 ">Lista</a>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

