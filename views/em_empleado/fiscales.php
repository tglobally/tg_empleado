<?php /** @var \tglobally\tg_empleado\controllers\controlador_em_empleado $controlador */ ?>
<?php include 'templates/em_empleado/modifica/secciones.php'; ?>
<div class="col-md-9 formulario">
    <div class="col-lg-12">

        <h3 class="text-center titulo-form">Hola, <?php echo $controlador->datos_session_usuario['adm_usuario_user']; ?> </h3>

        <div class="  form-main" id="form">
            <form method="post" action="./index.php?seccion=fiscales&accion=fiscales&session_id=<?php echo $controlador->session_id; ?>&registro_id=<?php echo $controlador->registro_id; ?>" class="form-additional">
                <?php echo $controlador->inputs->codigo; ?>
                <?php echo $controlador->inputs->codigo_bis; ?>
                <?php echo $controlador->inputs->descripcion; ?>
                <?php echo $controlador->inputs->nombre; ?>
                <?php echo $controlador->inputs->ap; ?>
                <?php echo $controlador->inputs->am; ?>
                <?php echo $controlador->inputs->select->dp_calle_pertenece_id; ?>
                <?php echo $controlador->inputs->select->cat_sat_regimen_fiscal_id; ?>
                <?php echo $controlador->inputs->select->org_puesto_id; ?>
                <?php echo $controlador->inputs->telefono; ?>
                <?php echo $controlador->inputs->rfc; ?>
                <?php echo $controlador->inputs->curp; ?>
                <a>modifica</a>
                <?php echo $controlador->inputs->nss; ?>
                <?php echo $controlador->inputs->select->im_registro_patronal_id; ?>
                <?php echo $controlador->inputs->fecha_inicio_rel_laboral; ?>
                <?php echo $controlador->inputs->cuenta_bancaria; ?>
                <?php echo $controlador->inputs->salario_diario; ?>
                <?php echo $controlador->inputs->salario_diario_integrado; ?>

                <div class="buttons col-md-12">
                    <div class="col-md-6 btn-ancho">
                        <button type="submit" class="btn btn-info btn-guarda col-md-12 " >Modifica</button>
                    </div>
                    <div class="col-md-6 btn-ancho">
                        <a href="index.php?seccion=em_empleado&accion=lista&session_id=<?php echo $controlador->session_id; ?>"  class="btn btn-info btn-guarda col-md-12 ">Lista</a>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

