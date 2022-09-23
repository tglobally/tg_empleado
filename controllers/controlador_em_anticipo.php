<?php
namespace tglobally\tg_empleado\controllers;

use gamboamartin\errores\errores;
use html\em_empleado_html;
use html\org_empresa_html;
use models\em_empleado;
use models\org_empresa;
use PDO;
use stdClass;
use tglobally\template_tg\html;

class controlador_em_anticipo extends \gamboamartin\empleado\controllers\controlador_em_anticipo {


    public function __construct(PDO $link, stdClass $paths_conf = new stdClass())
    {
        $html_base = new html();
        parent::__construct(link: $link, html: $html_base);
        $this->titulo_lista = 'Anticipo';
    }

}
