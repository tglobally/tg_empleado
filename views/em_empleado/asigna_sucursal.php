<?php /** @var \tglobally\tg_empleado\controllers\controlador_em_empleado $controlador */ ?>
<?php include 'templates/em_empleado/cuenta_bancaria/secciones.php'; ?>
<div class="col-md-9 formulario">
    <div class="col-lg-12">

        <h3 class="text-center titulo-form">Hola, <?php echo $controlador->datos_session_usuario['adm_usuario_user']; ?> </h3>

        <div class="  form-main" id="form">
            <form method="post" action="<?php echo $controlador->link_em_cuenta_bancaria_alta_bd; ?>" class="form-additional">
                <?php echo $controlador->inputs->em_empleado_id; ?>
                <?php echo $controlador->inputs->com_sucursal_id; ?>

                <div class="buttons col-md-12">
                    <div class="col-md-6 btn-ancho">
                        <button type="submit" class="btn btn-info btn-guarda col-md-12 " name="btn_action_next" value="cuenta_bancaria" >Asigna Sucursal</button>
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
                <span class="text-header">Clientes</span>
            </div>
            <div class="card-body">
                <div class="cont_tabla_sucursal  col-md-12">
                    <table class="table ">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Codigo</th>
                            <th>RFC Cliente</th>
                            <th>Razón Social Cliente</th>
                            <th>Sucursal Cliente</th>
                            <th>Modifica</th>
                            <th>Elimina</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($controlador->tg_empleado_sucursal->registros as $sucursal){
                            ?>
                            <tr>
                                <td><?php echo $sucursal['tg_empleado_sucursal_id']; ?></td>
                                <td><?php echo $sucursal['tg_empleado_sucursal_codigo']; ?></td>
                                <td><?php echo $sucursal['com_cliente_rfc']; ?></td>
                                <td><?php echo $sucursal['com_cliente_razon_social']; ?></td>
                                <td><?php echo $sucursal['dp_calle_descripcion'] .' '. $sucursal['com_sucursal_numero_exterior'] .' '.
                                        $sucursal['com_sucursal_numero_interior'] .' '. $sucursal['dp_colonia_descripcion'] .' '.
                                        $sucursal['dp_municipio_descripcion'] .' '. $sucursal['dp_estado_descripcion'];?></td>
                                <td><?php echo $sucursal['link_modifica']; ?></td>
                                <td><?php echo $sucursal['link_elimina']; ?></td>
                            </tr>
                        <?php } ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
