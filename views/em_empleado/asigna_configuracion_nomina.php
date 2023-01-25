<?php /** @var \tglobally\tg_empleado\controllers\controlador_em_empleado $controlador */ ?>
<?php include 'templates/em_empleado/asigna_configuracion_nomina/secciones.php'; ?>
<div class="col-md-9 formulario">
    <div class="col-lg-12">

        <h3 class="text-center titulo-form">Hola, <?php echo $controlador->datos_session_usuario['adm_usuario_user']; ?> </h3>

        <div class="  form-main" id="form">
            <form method="post" action="./index.php?seccion=<?php echo $controlador->seccion; ?>&accion=empleado_sucursal_alta_bd&session_id=<?php echo $controlador->session_id; ?>&registro_id=<?php echo $controlador->registro_id; ?>" class="form-additional">
                <?php echo $controlador->inputs->em_empleado_id; ?>
                <?php echo $controlador->inputs->codigo; ?>
                <?php echo $controlador->inputs->descripcion; ?>
                <?php echo $controlador->inputs->em_cuenta_bancaria_id; ?>
                <?php echo $controlador->inputs->nom_conf_nomina_id; ?>

                <div class="buttons col-md-12">
                    <div class="col-md-6 btn-ancho">
                        <button type="submit" class="btn btn-info btn-guarda col-md-12 " name="btn_action_next" value="cuenta_bancaria" >Asigna Configuracion Nomina</button>
                    </div>
                    <div class="col-md-6 btn-ancho">
                        <a href="index.php?seccion=em_empleado&accion=lista&session_id=<?php echo $controlador->session_id; ?>"  class="btn btn-info btn-guarda col-md-12 ">Lista</a>
                    </div>

                </div>
            </form>
        </div>
    </div>



</div>