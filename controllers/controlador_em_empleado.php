<?php
namespace tglobally\tg_empleado\controllers;

use gamboamartin\errores\errores;
use gamboamartin\organigrama\controllers\controlador_org_empresa;
use html\em_empleado_html;
use html\org_empresa_html;
use models\base\limpieza;
use models\em_empleado;
use models\org_empresa;
use PDO;
use stdClass;
use tglobally\template_tg\html;

class controlador_em_empleado extends \gamboamartin\empleado\controllers\controlador_em_empleado {


    public function __construct(PDO $link, stdClass $paths_conf = new stdClass())
    {
        $html_base = new html();
        parent::__construct(link: $link, html: $html_base);
        $this->titulo_lista = 'Empleados';
    }



}
