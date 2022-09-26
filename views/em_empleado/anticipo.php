<?php /** @var \tglobally\tg_empleado\controllers\controlador_em_empleado $controlador */ ?>
<?php include 'templates/em_empleado/anticipo/secciones.php'; ?>
<div class="col-md-9 formulario">
    <div class="col-lg-12">

        <h3 class="text-center titulo-form">Hola, <?php echo $controlador->datos_session_usuario['adm_usuario_user']; ?> </h3>

        <div class="  form-main" id="form">
            <form method="post" action="./index.php?seccion=em_empleado&accion=alta_anticipo_bd&session_id=<?php echo $controlador->session_id; ?>&registro_id=<?php echo $controlador->registro_id; ?>" class="form-additional">

                <?php echo $controlador->inputs->codigo; ?>
                <?php echo $controlador->inputs->codigo_bis; ?>
                <?php echo $controlador->inputs->descripcion; ?>
                <?php echo $controlador->inputs->monto; ?>
                <?php echo $controlador->inputs->fecha_prestacion; ?>
                <?php echo $controlador->inputs->select->em_empleado_id; ?>
                <?php echo $controlador->inputs->select->em_tipo_anticipo_id; ?>

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

    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="widget widget-box box-container widget-mylistings">

                    <div class="">
                        <table class="table table-striped footable-sort" data-sorting="true">
                            <th>Id</th>
                            <th>Codigo</th>
                            <th>Descripcion</th>
                            <th>Empleado</th>
                            <th>Monto</th>
                            <th>Fecha Prestacion</th>
                            <!--<th>Ver</th>
                            <th>Modifica</th>
                            <th>Elimina</th>-->

                            <tbody>
                            <?php foreach ($controlador->anticipos->registros as $anticipo){
                                ?>
                                <tr>
                                    <td><?php echo $anticipo['em_anticipo_id']; ?></td>
                                    <td><?php echo $anticipo['em_anticipo_codigo']; ?></td>
                                    <td><?php echo $anticipo['em_anticipo_descripcion']; ?></td>
                                    <td><?php echo $anticipo['em_empleado_nombre']." ".$anticipo['em_empleado_ap']." ".$anticipo['em_empleado_am']; ?></td>
                                    <td><?php echo $anticipo['em_anticipo_monto']; ?></td>
                                    <td><?php echo $anticipo['em_anticipo_fecha_prestacion']; ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        <div class="box-body">
                            * Total registros: <?php echo $controlador->anticipos->n_registros; ?><br />
                            * Fecha Hora: <?php echo $controlador->fecha_hoy; ?>
                        </div>
                    </div>
                </div> <!-- /. widget-table-->
            </div><!-- /.center-content -->
        </div>
    </div>

</div>

