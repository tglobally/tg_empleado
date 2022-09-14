<?php
namespace tglobally\tg_empleado\controllers;


use models\em_empleado;
use PDO;
use stdClass;
use tglobally\template_tg\html;

class controlador_em_empleado extends \gamboamartin\empleado\controllers\controlador_em_empleado {


    public function __construct(PDO $link, stdClass $paths_conf = new stdClass())
    {
        $html_base = new html();
        parent::__construct(link: $link, html: $html_base);

        $modelo = new em_empleado(link: $link);
        $this->modelo = $modelo;

        $this->titulo_lista = 'Empleados';
    }



}
