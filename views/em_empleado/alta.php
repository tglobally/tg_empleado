<?php /** @var \tglobally\tg_empleado\controllers\controlador_em_empleado $controlador */ ?>
<?php include 'templates/em_empleado/alta/secciones.php'; ?>
<div class="col-md-9 formulario">
    <div class="col-lg-12">

        <h3 class="text-center titulo-form">Hola, <?php echo $controlador->datos_session_usuario['adm_usuario_user']; ?> </h3>

        <div class="  form-main" id="form">
            <form method="post" action="./index.php?seccion=em_empleado&accion=alta_bd&session_id=<?php echo $controlador->session_id; ?>" class="form-additional">

                <?php echo $controlador->inputs->codigo; ?>
                <?php echo $controlador->inputs->nombre; ?>
                <?php echo $controlador->inputs->ap; ?>
                <?php echo $controlador->inputs->am; ?>
                <?php echo $controlador->inputs->dp_pais_id; ?>
                <?php echo $controlador->inputs->dp_estado_id; ?>
                <?php echo $controlador->inputs->direccion_pendiente_pais; ?>
                <?php echo $controlador->inputs->direccion_pendiente_estado; ?>
                <?php echo $controlador->inputs->dp_municipio_id; ?>
                <?php echo $controlador->inputs->dp_cp_id; ?>
                <?php echo $controlador->inputs->direccion_pendiente_municipio; ?>
                <?php echo $controlador->inputs->direccion_pendiente_cp; ?>
                <?php echo $controlador->inputs->dp_colonia_postal_id; ?>
                <?php echo $controlador->inputs->dp_calle_pertenece_id; ?>
                <?php echo $controlador->inputs->direccion_pendiente_colonia; ?>
                <?php echo $controlador->inputs->direccion_pendiente_calle_pertenece; ?>
                <?php echo $controlador->inputs->org_puesto_id; ?>
                <?php echo $controlador->inputs->telefono; ?>

                <div class="buttons col-md-12">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-info btn-guarda col-md-12 " name="btn_action_next" value="modifica">Guarda</button>
                    </div>
                    <div class="col-md-6 ">
                        <a href="index.php?seccion=em_empleado&accion=lista&session_id=<?php echo $controlador->session_id; ?>"  class="btn btn-info btn-guarda col-md-12 ">Regresar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
