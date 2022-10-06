<?php /** @var \tglobally\tg_empleado\controllers\controlador_em_empleado $controlador */ ?>
<?php include 'templates/em_empleado/cuenta_bancaria/secciones.php'; ?>
<div class="col-md-9 formulario">
    <div class="col-lg-12">

        <h3 class="text-center titulo-form">Hola, <?php echo $controlador->datos_session_usuario['adm_usuario_user']; ?> </h3>

        <div class="  form-main" id="form">
            <form method="post" action="<?php echo $controlador->link_em_cuenta_bancaria_alta_bd; ?>" class="form-additional">
                <?php echo $controlador->inputs->em_empleado_id; ?>
                <?php echo $controlador->inputs->bn_sucursal_id; ?>
                <?php echo $controlador->inputs->descripcion; ?>
                <?php echo $controlador->inputs->num_cuenta; ?>
                <?php echo $controlador->inputs->clabe; ?>

                <div class="buttons col-md-12">
                    <div class="col-md-6 btn-ancho">
                        <button type="submit" class="btn btn-info btn-guarda col-md-12 " name="btn_action_next" value="cuenta_bancaria" >Alta Cuenta Bancaria</button>
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
                <span class="text-header">Cuentas Bancarias</span>
            </div>
            <div class="card-body">
                <div class="cont_tabla_sucursal  col-md-12">
                    <table class="table ">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Codigo</th>
                            <th>Banco</th>
                            <th>Numero de Cuenta</th>
                            <th>Clabe</th>
                            <th>Modifica</th>
                            <th>Elimina</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($controlador->cuentas_bancarias->registros as $cuenta_bancaria){
                            ?>
                            <tr>
                                <td><?php echo $cuenta_bancaria['em_cuenta_bancaria_id']; ?></td>
                                <td><?php echo $cuenta_bancaria['em_cuenta_bancaria_codigo']; ?></td>
                                <td><?php echo $cuenta_bancaria['bn_banco_descripcion']; ?></td>
                                <td><?php echo $cuenta_bancaria['em_cuenta_bancaria_num_cuenta']; ?></td>
                                <td><?php echo $cuenta_bancaria['em_cuenta_bancaria_clabe']; ?></td>
                                <td><?php echo $cuenta_bancaria['link_modifica']; ?></td>
                                <td><?php echo $cuenta_bancaria['link_elimina']; ?></td>
                            </tr>
                        <?php } ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
