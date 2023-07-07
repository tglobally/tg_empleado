<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 0.48.13
 * @verclass 1.0.0
 * @created 2022-07-25
 * @final En proceso
 *
 */
namespace tglobally\tg_empleado\controllers;


use gamboamartin\errores\errores;
use PDO;
use stdClass;


class controlador_dp_pais extends \controllers\controlador_dp_pais {

    public function __construct(PDO $link, stdClass $paths_conf = new stdClass())
    {
        parent::__construct($link, $paths_conf);

        $sidebar = $this->init_sidebar();
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al inicializar sidebar', data: $sidebar);
            print_r($error);
            die('Error');
        }
    }

    private function init_sidebar(): stdClass|array
    {
        $menu_items = new stdClass();

        $menu_items->alta = $this->menu_item(menu_item_titulo: "Alta", link: $this->link_alta);


        $menu_items->alta['menu_seccion_active'] = true;
        $menu_items->alta['menu_lateral_active'] = true;


        $this->sidebar['alta']['titulo'] = "Pais";
        $this->sidebar['alta']['stepper_active'] = true;
        $this->sidebar['alta']['menu'] = array($menu_items->alta);

        return $menu_items;
    }

    public function menu_item(string $menu_item_titulo, string $link, bool $menu_seccion_active = false,
                              bool   $menu_lateral_active = false): array
    {
        $menu_item = array();
        $menu_item['menu_item'] = $menu_item_titulo;
        $menu_item['menu_seccion_active'] = $menu_seccion_active;
        $menu_item['link'] = $link;
        $menu_item['menu_lateral_active'] = $menu_lateral_active;

        return $menu_item;
    }

}
