<?php use config\views; ?>

<div class="col-md-3 secciones">
    <div class="col-md-12 int_secciones ">
        <div class="col-md-4 seccion">
            <img src="<?php echo (new views())->url_assets.'img/stepper/1.svg'?>" class="img-seccion">
        </div>
        <div class="col-md-8">
            <h3>Alta Empleado</h3>
            <?php include "templates/em_empleado/_base/buttons/1.azul.alta.php"; ?>
            <hr class="hr-menu-lateral">
            <?php include "templates/em_empleado/_base/buttons/2.gris.fiscales.php"; ?>
            <hr class="hr-menu-lateral">
            <?php include "templates/em_empleado/_base/buttons/3.gris.imss.php"; ?>
            <hr class="hr-menu-lateral">
            <?php include "templates/em_empleado/_base/buttons/4.gris.cuenta_bancaria.php"; ?>
        </div>
    </div>
</div>