<?php
namespace models;
use base\orm\modelo;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class tg_empleado_sucursal extends modelo{

    public function __construct(PDO $link){
        $tabla = __CLASS__;
        $columnas = array($tabla=>false, 'em_empleado'=>$tabla,'com_sucursal'=>$tabla);
        $campos_obligatorios = array();

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas);
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