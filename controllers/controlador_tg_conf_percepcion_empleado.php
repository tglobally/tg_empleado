<?php
namespace tglobally\tg_empleado\controllers;

use gamboamartin\system\_ctl_base;
use gamboamartin\system\links_menu;
use html\tg_empleado_sucursal_html;
use tglobally\tg_empleado\models\tg_conf_percepcion_empleado;

use PDO;
use stdClass;

class controlador_tg_conf_percepcion_empleado extends _ctl_base
{
    public function __construct(PDO $link, \gamboamartin\template\html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass())
    {
        $modelo = new tg_conf_percepcion_empleado(link: $link);
        $html_ = new tg_empleado_sucursal_html(html: $html);
        $obj_link = new links_menu(link:$link,registro_id: $this->registro_id);

        parent::__construct(html: $html_, link: $link, modelo: $modelo, obj_link: $obj_link,paths_conf: $paths_conf);

    }

}
