<?php /** @var \gamboamartin\empleado\models\em_anticipo $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>

<?php include 'templates/em_anticipo/ver_abonos/secciones.php'; ?>
<div class="col-md-9 formulario">
    <div class="col-lg-12">

        <h3 class="text-center titulo-form">Hola, <?php echo $controlador->datos_session_usuario['adm_usuario_user']; ?> </h3>

        <div class="  form-main" id="form">
            <form method="post" action="<?php echo $controlador->link_em_abono_anticipo_alta_bd; ?>&em_anticipo_id=<?php echo $controlador->em_anticipo_id; ?>" class="form-additional">

            <?php echo $controlador->inputs->codigo; ?>
            <?php echo $controlador->inputs->select->em_tipo_anticipo_id; ?>
            <?php echo $controlador->inputs->descripcion; ?>
            <?php echo $controlador->inputs->select->em_empleado_id; ?>
            <?php echo $controlador->inputs->monto; ?>
            <?php echo $controlador->inputs->fecha_prestacion; ?>

            <div class="buttons col-md-12">
                <div class="col-md-6 btn-ancho">
                    <button type="submit" class="btn btn-info btn-guarda col-md-12 " name="btn_action_next" value="anticipo" >Alta Anticipo</button>
                </div>
                <div class="col-md-6 btn-ancho">
                    <a href="index.php?seccion=em_empleado&accion=lista&session_id=<?php echo $controlador->session_id; ?>"  class="btn btn-info btn-guarda col-md-12 ">Lista</a>
                </div>

            </div>
            </form>
        </div>
    </div>

    <div class="lista">
        <div class="card">
            <div class="card-header">
                <span class="text-header">Anticipos</span>
            </div>
            <div class="card-body">
                <div class="cont_tabla_sucursal  col-md-12">
                    <table class="table ">
                        <thead>
                        <tr>
                            <th data-breakpoints="xs sm md" data-type="html">Id</th>
                            <th data-breakpoints="xs sm md" data-type="html">Codigo</th>
                            <th data-breakpoints="xs sm md" data-type="html">Descripcion</th>
                            <th data-breakpoints="xs sm md" data-type="html">Monto</th>
                            <th data-breakpoints="xs sm md" data-type="html">Fecha</th>
                            <th data-breakpoints="xs sm md" data-type="html">Tipo Abono</th>
                            <th data-breakpoints="xs sm md" data-type="html">Forma Pago</th>

                            <th data-breakpoints="xs md" class="control"  data-type="html">Modifica</th>
                            <th data-breakpoints="xs md" class="control"  data-type="html">Elimina</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php foreach ($controlador->abonos->registros as $abono){?>
                            <tr>
                                <td><?php echo $abono['em_abono_anticipo_id']; ?></td>
                                <td><?php echo $abono['em_abono_anticipo_codigo']; ?></td>
                                <td><?php echo $abono['em_abono_anticipo_descripcion']; ?></td>
                                <td><?php echo $abono['em_abono_anticipo_monto']; ?></td>
                                <td><?php echo $abono['em_abono_anticipo_fecha']; ?></td>
                                <td><?php echo $abono['em_tipo_abono_anticipo_descripcion']; ?></td>
                                <td><?php echo $abono['cat_sat_forma_pago_descripcion']; ?></td>
                                <td><?php echo $abono['link_modifica']; ?></td>
                                <td><?php echo $abono['link_elimina']; ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>