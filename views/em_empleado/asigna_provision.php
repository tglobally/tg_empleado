<?php /** @var \tglobally\tg_empleado\controllers\controlador_em_empleado $controlador */ ?>

<?php (new \tglobally\template_tg\template())->sidebar(controlador: $controlador,seccion_step: 7); ?>

<div class="col-md-9 formulario">
    <div class="col-lg-12">

        <h3 class="text-center titulo-form">
            Hola, <?php echo $controlador->datos_session_usuario['adm_usuario_user']; ?> </h3>

        <div class="  form-main" id="form">
            <form method="post" action="<?php echo $controlador->link_tg_empleado_asigna_provision_bd; ?>"
                  class="form-additional">
                <?php echo $controlador->inputs->em_empleado_id; ?>
                <?php echo $controlador->inputs->com_sucursal_id; ?>
                <?php echo $controlador->inputs->org_sucursal_id; ?>


                <div class="control-group">
                    <div class="control-group col-sm-6">
                        <div class="controls">
                            <input type="checkbox" class="form-check-input" name="prima_vacacional" value="PRIMA VACACIONAL">
                            <label class="form-check-label" for="flexCheckDefault">Prima Vacacional</label>
                        </div>
                    </div>
                    <div class="control-group col-sm-6">
                        <div class="controls">
                            <input type="checkbox" class="form-check-input" name="vacaciones" value="VACACIONES">
                            <label class="form-check-label" for="flexCheckDefault">Vacaciones</label>
                        </div>
                    </div>
                    <div class="control-group col-sm-6">
                        <div class="controls">
                            <input type="checkbox" class="form-check-input" name="prima_antiguedad" value="PRIMA DE ANTIGÜEDAD">
                            <label class="form-check-label" for="flexCheckDefault">Prima Antigüedad</label>
                        </div>
                    </div>
                    <div class="control-group col-sm-6">
                        <div class="controls">
                            <input type="checkbox" class="form-check-input" name="aguinaldo" value="GRATIFICACIÓN ANUAL (AGUINALDO)">
                            <label class="form-check-label" for="flexCheckDefault">Gratificación Anual (Aguinaldo)</label>
                        </div>
                    </div>
                </div>


                <div class="buttons col-md-12">
                    <div class="col-md-6 btn-ancho">
                        <button type="submit" class="btn btn-info btn-guarda col-md-12 " name="btn_action_next"
                                value="asigna_sucursal">Procesar
                        </button>
                    </div>
                    <div class="col-md-6 btn-ancho">
                        <a href="<?php echo $controlador->link_lista; ?>" class="btn btn-info btn-guarda col-md-12 ">Lista</a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <div class="lista">
        <div class="card">
            <div class="card-header">
                <span class="text-header">Provisiones Asignadas</span>
            </div>
            <div class="card-body">
                <?php echo $controlador->contenido_table; ?>
            </div> <!-- /. widget-table-->
        </div>
    </div>
</div>

