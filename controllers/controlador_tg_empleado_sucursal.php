<?php
namespace tglobally\tg_empleado\controllers;


use gamboamartin\errores\errores;
use gamboamartin\system\links_menu;
use gamboamartin\system\system;
use html\tg_empleado_sucursal_html;
use models\tg_empleado_sucursal;
use PDO;
use stdClass;

class controlador_tg_empleado_sucursal extends system
{
    public array $keys_selects = array();
    public function __construct(PDO $link, \gamboamartin\template\html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass())
    {
        $modelo = new tg_empleado_sucursal(link: $link);
        $html_ = new tg_empleado_sucursal_html(html: $html);
        $obj_link = new links_menu($this->registro_id);
        parent::__construct(html: $html_, link: $link, modelo: $modelo, obj_link: $obj_link, paths_conf: $paths_conf);

        $this->titulo_lista = 'Manifiesto';
    }

    public function asignar_propiedad(string $identificador, mixed $propiedades)
    {
        if (!array_key_exists($identificador,$this->keys_selects)){
            $this->keys_selects[$identificador] = new stdClass();
        }

        foreach ($propiedades as $key => $value){
            $this->keys_selects[$identificador]->$key = $value;
        }
    }

}
