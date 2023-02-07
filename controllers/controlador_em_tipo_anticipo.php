<?php
namespace tglobally\tg_empleado\controllers;

use gamboamartin\errores\errores;
use html\em_empleado_html;
use html\org_empresa_html;
use tglobally\tg_empleado\models\em_empleado;
use tglobally\tg_empleado\models\org_empresa;
use PDO;
use stdClass;
use tglobally\template_tg\html;

class controlador_em_tipo_anticipo extends \gamboamartin\empleado\controllers\controlador_em_tipo_anticipo {


    public function __construct(PDO $link, stdClass $paths_conf = new stdClass())
    {
        $html_base = new html();
        parent::__construct(link: $link, html: $html_base);
        $this->titulo_lista = 'Tipo anticipo';

        $this->sidebar['lista']['titulo'] = "Tipo Anticipo";
        $this->sidebar['lista']['menu'] = array(
            $this->menu_item(menu_item_titulo: "Alta", link: $this->link_alta,menu_seccion_active: true,
                menu_lateral_active: true));

        $this->sidebar['alta']['titulo'] = "Alta Tipo Anticipo";
        $this->sidebar['alta']['stepper_active'] = true;
        $this->sidebar['alta']['menu'] = array(
            $this->menu_item(menu_item_titulo: "Alta", link: $this->link_alta,menu_lateral_active: true));

        $this->sidebar['modifica']['titulo'] = "Modifica Tipo Anticipo";
        $this->sidebar['modifica']['stepper_active'] = true;
        $this->sidebar['modifica']['menu'] = array(
            $this->menu_item(menu_item_titulo: "Modifica", link: $this->link_alta,menu_lateral_active: true));

    }

    public function menu_item(string $menu_item_titulo, string $link, bool $menu_seccion_active = false,bool $menu_lateral_active = false): array
    {
        $menu_item = array();
        $menu_item['menu_item'] = $menu_item_titulo;
        $menu_item['menu_seccion_active'] = $menu_seccion_active;
        $menu_item['link'] = $link;
        $menu_item['menu_lateral_active'] = $menu_lateral_active;

        return $menu_item;
    }
}
