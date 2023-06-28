<?php

namespace tglobally\tg_empleado\models;

use base\orm\modelo;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class em_empleado extends \gamboamartin\empleado\models\em_empleado {

    public function __construct(PDO $link){
        parent::__construct(link: $link);
    }

    public function alta_bd(array $keys_integra_ds = array('codigo', 'descripcion')): array|stdClass
    {
        $alta_bd = parent::alta_bd($keys_integra_ds);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al dar de alta empleado',data: $alta_bd);
        }

        /*$registros_empleado_sucursal = $this->genera_registro_empleado_sucursal(em_empleado: $alta_bd);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar registros de empleado-sucursal',
                data: $registros_empleado_sucursal);
        }

        $alta_empleado_sucursal = (new tg_empleado_sucursal($this->link))->alta_registro(registro: $registros_empleado_sucursal);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al dar de alta empleado-sucursal', data: $alta_empleado_sucursal);
        }*/

        return $alta_bd;

    }

    private function genera_registro_empleado_sucursal(mixed $em_empleado) : array{
        $descripcion = $em_empleado->registro_id.$em_empleado->registro['em_empleado_descripcion'];
        $codigo = $em_empleado->registro_id.$em_empleado->registro['em_empleado_codigo'];
        $descripcion_select = $em_empleado->registro_id.$em_empleado->registro['em_empleado_descripcion_select'];
        $codigo_bis= $em_empleado->registro_id.$em_empleado->registro['em_empleado_codigo_bis'];
        $alias = $em_empleado->registro_id.$em_empleado->registro['em_empleado_alias'];
        $em_empleado_id = $em_empleado->registro_id;
        $com_sucursal_id = 1;
        $fecha = date('Y-m-d');;

        return array('descripcion' => $descripcion,'codigo' => $codigo,'descripcion_select' => $descripcion_select,
            'codigo_bis' => $codigo_bis,'alias' => $alias,'em_empleado_id' => $em_empleado_id,
            'com_sucursal_id' => $com_sucursal_id,'fecha' => $fecha);
    }
}