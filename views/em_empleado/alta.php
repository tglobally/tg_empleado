<?php /** @var \tglobally\tg_empleado\controllers\controlador_em_anticipo $controlador */ ?>
<?php (new \tglobally\template_tg\template())->sidebar($controlador); ?>

<div class="col-md-9 formulario">
    <div class="col-lg-12">

        <h3 class="text-center titulo-form">
            Hola, <?php echo $controlador->datos_session_usuario['adm_usuario_user']; ?> </h3>

        <div class="  form-main" id="form">
            <form method="post" action="<?php echo $controlador->link_alta_bd; ?>" class="form-additional">
                <?php echo $controlador->inputs->com_sucursal_id; ?>
                <?php echo $controlador->inputs->nombre; ?>
                <?php echo $controlador->inputs->ap; ?>
                <?php echo $controlador->inputs->am; ?>
                <?php echo $controlador->inputs->codigo; ?>
                <?php echo $controlador->inputs->fecha_antiguedad; ?>
                <?php echo $controlador->inputs->dp_pais_id; ?>
                <?php echo $controlador->inputs->dp_estado_id; ?>
                <?php echo $controlador->inputs->dp_municipio_id; ?>
                <?php echo $controlador->inputs->dp_cp_id; ?>
                <?php echo $controlador->inputs->dp_colonia_postal_id; ?>
                <?php echo $controlador->inputs->dp_calle_pertenece_id; ?>
                <?php echo $controlador->inputs->cat_sat_regimen_fiscal_id; ?>
                <?php echo $controlador->inputs->org_puesto_id; ?>
                <?php echo $controlador->inputs->cat_sat_tipo_regimen_nom_id; ?>
                <?php echo $controlador->inputs->cat_sat_tipo_jornada_nom_id; ?>
                <?php echo $controlador->inputs->rfc; ?>
                <?php echo $controlador->inputs->nss; ?>
                <?php echo $controlador->inputs->curp; ?>
                <?php echo $controlador->inputs->telefono; ?>
                <?php echo $controlador->inputs->em_registro_patronal_id; ?>
                <?php echo $controlador->inputs->em_centro_costo_id; ?>
                <?php echo $controlador->inputs->salario_diario; ?>
                <?php echo $controlador->inputs->salario_diario_integrado; ?>
                <?php echo $controlador->inputs->salario_total; ?>
                <div class="buttons col-md-12">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-info btn-guarda col-md-12 " name="btn_action_next"
                                value="modifica">Guarda
                        </button>
                    </div>
                    <div class="col-md-6 ">
                        <a href="<?php echo $controlador->link_lista; ?>" class="btn btn-info btn-guarda col-md-12 ">Regresar</a>
                    </div>
                </div>
                <div class="buttons col-md-12">
                    <div class="col-md-4 ">
                        <a href="<?php echo $controlador->link_dp_pais_alta; ?>" class="btn btn-info btn-guarda col-md-12 ">Nuevo País</a>
                    </div>
                    <div class="col-md-4 ">
                        <a href="<?php echo $controlador->link_dp_estado_alta; ?>" class="btn btn-info btn-guarda col-md-12 ">Nuevo Estado</a>
                    </div>
                    <div class="col-md-4 ">
                        <a href="<?php echo $controlador->link_dp_municipio_alta; ?>" class="btn btn-info btn-guarda col-md-12 ">Nuevo Municipio</a>
                    </div>
                </div>
                <div class="buttons col-md-12">
                    <div class="col-md-4 ">
                        <a href="<?php echo $controlador->link_dp_cp_alta; ?>" class="btn btn-info btn-guarda col-md-12 ">Nuevo CP</a>
                    </div>
                    <div class="col-md-4 ">
                        <a href="<?php echo $controlador->link_dp_colonia_alta; ?>" class="btn btn-info btn-guarda col-md-12 ">Nueva Colonia</a>
                    </div>
                    <div class="col-md-4 ">
                        <a href="<?php echo $controlador->link_dp_calle_alta; ?>" class="btn btn-info btn-guarda col-md-12 ">Nueva Calle</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
