<?php
namespace tglobally\tg_empleado\controllers;

use base\controller\controler;
use gamboamartin\errores\errores;
use gamboamartin\system\_ctl_base;
use gamboamartin\system\links_menu;
use html\tg_empleado_sucursal_html;
use tglobally\tg_empleado\models\tg_conf_provisiones_empleado;
use tglobally\tg_empleado\models\tg_empleado_sucursal;
use PDO;
use stdClass;

class controlador_tg_conf_provisiones_empleado extends _ctl_base
{
    public function __construct(PDO $link, \gamboamartin\template\html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass())
    {
        $modelo = new tg_conf_provisiones_empleado(link: $link);
        $html_ = new tg_empleado_sucursal_html(html: $html);
        $obj_link = new links_menu(link:$link,registro_id: $this->registro_id);

        parent::__construct(html: $html_, link: $link, modelo: $modelo, obj_link: $obj_link,paths_conf: $paths_conf);

    }

    public function get_provisiones(bool $header, bool $ws = true): array|stdClass
    {
        $keys['tg_conf_provision'] = array('id', 'descripcion', 'codigo', 'estado');
        $keys['com_sucursal'] = array('id', 'descripcion', 'codigo', 'codigo_bis');
        $keys['org_sucursal'] = array('id', 'descripcion', 'codigo', 'codigo_bis');
        $keys['em_empleado'] = array('id', 'descripcion', 'codigo', 'codigo_bis');

        $salida = $this->get_out(header: $header, keys: $keys, ws: $ws);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar salida', data: $salida, header: $header, ws: $ws);
        }

        return $salida;
    }
}
