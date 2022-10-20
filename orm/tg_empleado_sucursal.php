<?php
namespace models;
use base\orm\modelo;
use gamboamartin\comercial\models\com_sucursal;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class tg_empleado_sucursal extends modelo{

    public function __construct(PDO $link){
        $tabla = __CLASS__;
        $columnas = array($tabla=>false, 'em_empleado'=>$tabla,'com_sucursal'=>$tabla,'com_cliente'=>'com_sucursal',
            'dp_calle_pertenece'=>'com_sucursal', 'dp_calle'=>'dp_calle_pertenece',
            'dp_colonia_postal'=>'dp_calle_pertenece', 'dp_colonia'=>'dp_colonia_postal','dp_cp'=>'dp_colonia_postal',
            'dp_municipio'=>'dp_cp', 'dp_estado'=>'dp_municipio','dp_pais'=>'dp_estado');
        $campos_obligatorios = array();
        $campos_view = array('com_sucursal_id' => array('type' => 'selects', 'model' => new com_sucursal($link)),
            'em_empleado_id' => array('type' => 'selects', 'model' => new em_empleado($link)));
        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas,campos_view: $campos_view);
    }

    public function get_tg_empleado_sucursal_empleado(int $em_empleado_id): array|stdClass
    {
        if($em_empleado_id <=0){
            return $this->error->error(mensaje: 'Error $em_empleado_id debe ser mayor a 0', data: $em_empleado_id);
        }

        $filtro['em_empleado.id'] = $em_empleado_id;
        $r_tg_empleado_sucursal = $this->filtro_and(filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener cuentas bancarias', data: $r_tg_empleado_sucursal);
        }

        return $r_tg_empleado_sucursal;
    }
}