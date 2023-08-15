<?php
namespace tglobally\tg_empleado\models;

use base\orm\_modelo_parent;
use PDO;

class tg_conf_provision extends _modelo_parent {

    public function __construct(PDO $link){
        $tabla = 'tg_conf_provision';
        $columnas = array($tabla=>false, 'nom_conf_empleado'=>$tabla, 'tg_tipo_provision' => $tabla,
            'em_cuenta_bancaria' => 'nom_conf_empleado', 'em_empleado' => 'em_cuenta_bancaria');
        $campos_obligatorios = array('descripcion','codigo');

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas);

        $this->NAMESPACE = __NAMESPACE__;
    }

}