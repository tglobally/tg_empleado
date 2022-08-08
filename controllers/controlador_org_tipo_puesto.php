<?php
namespace tglobally\tg_empleado\controllers;

use PDO;
use stdClass;
use tglobally\template_tg\html;

class controlador_org_tipo_puesto extends \gamboamartin\organigrama\controllers\controlador_org_tipo_puesto {


    public function __construct(PDO $link, stdClass $paths_conf = new stdClass()){
        $html_base = new html();
        parent::__construct( link: $link, html: $html_base);
        $this->titulo_lista = 'Tipo puesto';
    }

}
