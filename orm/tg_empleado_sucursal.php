<?php
namespace tglobally\tg_empleado\models;
use base\orm\_modelo_parent;
use base\orm\modelo;
use gamboamartin\comercial\models\com_sucursal;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class tg_empleado_sucursal extends _modelo_parent{

    public function __construct(PDO $link){
        $tabla = "tg_empleado_sucursal";
        $columnas = array($tabla=>false, 'em_empleado'=>$tabla,'com_sucursal'=>$tabla,'com_cliente'=>'com_sucursal',
            'dp_calle_pertenece'=>'com_sucursal', 'dp_calle'=>'dp_calle_pertenece',
            'dp_colonia_postal'=>'dp_calle_pertenece', 'dp_colonia'=>'dp_colonia_postal','dp_cp'=>'dp_colonia_postal',
            'dp_municipio'=>'dp_cp', 'dp_estado'=>'dp_municipio','dp_pais'=>'dp_estado');
        $campos_obligatorios = array();

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas);
    }

    public function alta_bd(array $keys_integra_ds = array('codigo', 'descripcion')): array|stdClass
    {
        if(!isset($this->registro['codigo'])){

            $this->registro['codigo'] =  $this->get_codigo_aleatorio();
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al generar codigo aleatorio',data:  $this->registro);
            }
        }

        if(!isset($this->registro['descripcion'])){
            $this->registro['descripcion'] = $this->registro['codigo']. ' ';
            $this->registro['descripcion'] .= $this->registro['em_empleado_id']. ' ';
            $this->registro['descripcion'] .= $this->registro['com_sucursal_id'];
        }

        $r_alta_bd = parent::alta_bd();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al dar de alta sucursal empleado',data:  $r_alta_bd);
        }
        return $r_alta_bd;
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

    public function com_sucursal(int $em_empleado_id): array|bool
    {
        if($em_empleado_id <= 0){
            return  $this->error->error(mensaje: 'Error $em_empleado_id debe ser mayor a 0', data: $em_empleado_id);
        }

        $filtro["tg_empleado_sucursal.em_empleado_id"] =  $em_empleado_id;
        $order = array("tg_empleado_sucursal.fecha" => "DESC");
        $resultado = $this->filtro_and(columnas: array("com_sucursal_predeterminado","tg_empleado_sucursal_fecha"),
            filtro: $filtro, limit: 1, order: $order);
        if(errores::$error){
            return  $this->error->error(mensaje: 'Error al obtener sucursal del empleado',data: $resultado);
        }

        if ($resultado->n_registros === 0){
            return false;
        }

        if ($resultado->registros[0]["com_sucursal_predeterminado"] === "inactivo"){
            return false;
        }

        return true;
    }
}