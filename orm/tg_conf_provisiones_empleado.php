<?php
namespace tglobally\tg_empleado\models;

use base\orm\_modelo_parent;
use PDO;

class tg_conf_provisiones_empleado extends _modelo_parent {

    public function __construct(PDO $link){
        $tabla = 'tg_conf_provisiones_empleado';
        $columnas = array($tabla=>false, 'tg_conf_provision' => $tabla, 'em_empleado' => $tabla,
            'tg_tipo_provision' => $tabla, 'com_sucursal' => 'tg_conf_provision', 'org_sucursal' => 'tg_conf_provision');
        $campos_obligatorios = array();

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas);

        $this->NAMESPACE = __NAMESPACE__;
    }

}