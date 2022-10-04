<?php /** @var \tglobally\tg_empleado\controllers\controlador_em_anticipo $controlador */ ?>
<?php include 'templates/em_anticipo/modifica/secciones.php'; ?>
<div class="col-md-9 formulario">
    <div class="col-lg-12">

        <h3 class="text-center titulo-form">Hola, <?php echo $controlador->datos_session_usuario['adm_usuario_user']; ?> </h3>

        <div class="  form-main" id="form">
            <form method="post" action="<?php echo $controlador->link_em_abono_anticipo_modifica_bd; ?>&em_abono_anticipo_id=<?php echo $controlador->em_abono_anticipo_id; ?>&em_anticipo_id=<?php echo $controlador->em_anticipo_id; ?>" class="form-additional">

                <?php echo $controlador->inputs->codigo; ?>
                <?php echo $controlador->inputs->em_anticipo_id; ?>
                <?php echo $controlador->inputs->descripcion; ?>
                <?php echo $controlador->inputs->em_tipo_abono_anticipo_id; ?>
                <?php echo $controlador->inputs->cat_sat_forma_pago_id; ?>
                <?php echo $controlador->inputs->monto; ?>
                <?php echo $controlador->inputs->fecha; ?>

                <div class="buttons col-md-12">
                    <div class="col-md-6 btn-ancho">
                        <button type="submit" class="btn btn-info btn-guarda col-md-12 " name="btn_action_next" value="anticipo" >Modifica</button>
                    </div>
                    <div class="col-md-6 btn-ancho">
                        <a href="index.php?seccion=em_anticipo&accion=abono&session_id=<?php echo $controlador->session_id; ?>&registro_id=<?php echo $controlador->registro_id; ?>&em_anticipo_id=<?php echo $controlador->em_anticipo_id; ?>"  class="btn btn-info btn-guarda col-md-12 ">Regresar</a>
                    </div>

                </div>
            </form>
        </div>
    </div>

</div>

