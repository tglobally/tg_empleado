<?php /** @var \tglobally\tg_empleado\controllers\controlador_em_empleado $controlador */ ?>
<?php include 'templates/em_empleado/anticipo/secciones.php'; ?>
<div class="col-md-9 formulario">
    <div class="col-lg-12">

        <h3 class="text-center titulo-form">Hola, <?php echo $controlador->datos_session_usuario['adm_usuario_user']; ?> </h3>

        <div class="  form-main" id="form">
            <form method="post" action="<?php echo $controlador->link_em_anticipo_alta_bd; ?>"" class="form-additional">

                <?php echo $controlador->inputs->em_empleado_id; ?>
                <?php echo $controlador->inputs->em_tipo_anticipo_id; ?>
                <?php echo $controlador->inputs->descripcion; ?>
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
                            <th>Id</th>
                            <th>Codigo</th>
                            <th>Descripcion</th>
                            <th>Empleado</th>
                            <th>Monto</th>
                            <th>Fecha Prestacion</th>
                            <th>Abono</th>
                            <th>Modifica</th>
                            <th>Elimina</th>
                        </tr>
                        </thead>
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
                                <td><?php echo $anticipo['link_abono']; ?></td>
                                <td><?php echo $anticipo['link_modifica']; ?></td>
                                <td><?php echo $anticipo['link_elimina']; ?></td>
                            </tr>
                        <?php } ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

