<?php
namespace tglobally\tg_empleado\controllers;

use html\org_empresa_html;
use models\org_empresa;
use PDO;
use stdClass;
use tglobally\template_tg\html;

class controlador_em_anticipo extends \gamboamartin\empleado\controllers\controlador_em_anticipo {


    public function __construct(PDO $link, stdClass $paths_conf = new stdClass())
    {
        $html_base = new html();
        parent::__construct(link: $link, html: $html_base);
        $this->titulo_lista = 'Anticipos';

        $this->sidebar['lista']['titulo'] = "Anticipos";
        $this->sidebar['lista']['menu'] = array(
            $this->menu_item(menu_item_titulo: "Alta", link: $this->link_alta,menu_seccion_active: true,
                menu_lateral_active: true),
            $this->menu_item(menu_item_titulo: "Reporte Empresa", link: $this->link_em_abono_anticipo_reporte_empresa,
                menu_seccion_active: true));

        $this->sidebar['alta']['titulo'] = "Alta Anticipo";
        $this->sidebar['alta']['stepper_active'] = true;
        $this->sidebar['alta']['menu'] = array(
            $this->menu_item(menu_item_titulo: "Alta", link: $this->link_alta,menu_lateral_active: true),
            $this->menu_item(menu_item_titulo: "Reporte Empresa", link: $this->link_em_abono_anticipo_reporte_empresa,
                menu_seccion_active: true));

        $this->sidebar['modifica']['titulo'] = "Modifica Anticipos";
        $this->sidebar['modifica']['stepper_active'] = true;
        $this->sidebar['modifica']['menu'] = array(
            $this->menu_item(menu_item_titulo: "Modifica", link: $this->link_alta,menu_lateral_active: true),
            $this->menu_item(menu_item_titulo: "Reporte Empresa", link: $this->link_em_abono_anticipo_reporte_empresa,
                menu_seccion_active: true));

        $this->sidebar['reporte_empresa']['titulo'] = "Reporte Empresa";
        $this->sidebar['reporte_empresa']['stepper_active'] = true;
        $this->sidebar['reporte_empresa']['menu'] = array(
            $this->menu_item(menu_item_titulo: "Alta", link: $this->link_alta,menu_seccion_active: true),
            $this->menu_item(menu_item_titulo: "Reporte Empresa", link: $this->link_em_abono_anticipo_reporte_empresa,
        menu_seccion_active: true, menu_lateral_active: true));

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
