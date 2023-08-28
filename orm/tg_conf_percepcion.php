<?php
namespace tglobally\tg_empleado\models;

use base\orm\_modelo_parent;
use PDO;

class tg_conf_percepcion extends _modelo_parent {

    public function __construct(PDO $link){
        $tabla = 'tg_conf_percepcion';
        $columnas = array($tabla=>false, 'com_sucursal'=>$tabla);
        $campos_obligatorios = array('descripcion','codigo');

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas);

        $this->NAMESPACE = __NAMESPACE__;
    }

}