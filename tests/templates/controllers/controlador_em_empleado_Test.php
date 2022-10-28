<?php
namespace tests\templates\directivas;


use gamboamartin\errores\errores;
use gamboamartin\test\liberator;
use gamboamartin\test\test;
use stdClass;
use tglobally\tg_empleado\controllers\controlador_em_empleado;


class controlador_em_empleado_Test extends test {
    public errores $errores;
    private stdClass $paths_conf;
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->errores = new errores();
        $this->paths_conf = new stdClass();
        $this->paths_conf->generales = '/var/www/html/tg_empleado/config/generales.php';
        $this->paths_conf->database = '/var/www/html/tg_empleado/config/database.php';
        $this->paths_conf->views = '/var/www/html/tg_empleado/config/views.php';
    }

    public function test_clean_post(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'em_empleado';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';

        $ctl = new controlador_em_empleado(link: $this->link, paths_conf: $this->paths_conf);
        $ctl = new liberator($ctl);
        $_POST['btn_action_next'] = 'XXX';
        $resultado = $ctl->clean_post();

        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEmpty($resultado);

        errores::$error = false;
    }





}

