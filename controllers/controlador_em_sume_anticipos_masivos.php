<?php
namespace tglobally\tg_empleado\controllers;

use PDO;
use stdClass;
use tglobally\template_tg\html;

class controlador_org_sume_anticipos_masivos extends \gamboamartin\organigrama\controllers\controlador_em_anticipo {

    public function __construct(PDO $link, stdClass $paths_conf = new stdClass()){
        $html_base = new html();
        parent::__construct( link: $link, html: $html_base);
        $this->titulo_lista = 'Anticipos Masivos';
    }

}
