<?php
namespace tglobally\tg_empleado\controllers;

use gamboamartin\empleado\models\em_anticipo;
use gamboamartin\errores\errores;
use gamboamartin\plugins\exportador;
use html\org_empresa_html;
use tglobally\tg_empleado\models\em_empleado;
use tglobally\tg_empleado\models\org_empresa;
use PDO;
use stdClass;
use tglobally\template_tg\html;
use tglobally\tg_empleado\models\tg_empleado_sucursal;

class controlador_em_anticipo extends \gamboamartin\empleado\controllers\controlador_em_anticipo {

    public string $link_em_anticipo_exportar_cliente = '';
    public string $link_em_anticipo_exportar_empresa = '';
    public function __construct(PDO $link, stdClass $paths_conf = new stdClass())
    {
        $html_base = new html();
        parent::__construct(link: $link, html: $html_base);
        $this->titulo_lista = 'Anticipos';

        $this->sidebar['lista']['titulo'] = "Anticipos";
        $this->sidebar['lista']['menu'] = array(
            $this->menu_item(menu_item_titulo: "Alta", link: $this->link_alta,menu_seccion_active: true,
                menu_lateral_active: true),
            $this->menu_item(menu_item_titulo: "Reporte Empresa", link: $this->link_em_anticipo_reporte_empresa,
                menu_seccion_active: true),
            $this->menu_item(menu_item_titulo: "Reporte Cliente", link: $this->link_em_anticipo_reporte_cliente,
                menu_seccion_active: true),
            $this->menu_item(menu_item_titulo: "Reporte Empleado", link: $this->link_em_anticipo_reporte_empleado,
                menu_seccion_active: true));

        $this->sidebar['alta']['titulo'] = "Alta Anticipo";
        $this->sidebar['alta']['stepper_active'] = true;
        $this->sidebar['alta']['menu'] = array(
            $this->menu_item(menu_item_titulo: "Alta", link: $this->link_alta,menu_lateral_active: true),
            $this->menu_item(menu_item_titulo: "Reporte Empresa", link: $this->link_em_anticipo_reporte_empresa,
                menu_seccion_active: true),
            $this->menu_item(menu_item_titulo: "Reporte Cliente", link: $this->link_em_anticipo_reporte_cliente,
                menu_seccion_active: true),
            $this->menu_item(menu_item_titulo: "Reporte Empleado", link: $this->link_em_anticipo_reporte_empleado,
                menu_seccion_active: true));

        $this->sidebar['modifica']['titulo'] = "Modifica Anticipos";
        $this->sidebar['modifica']['stepper_active'] = true;
        $this->sidebar['modifica']['menu'] = array(
            $this->menu_item(menu_item_titulo: "Modifica", link: $this->link_alta,menu_lateral_active: true),
            $this->menu_item(menu_item_titulo: "Reporte Empresa", link: $this->link_em_anticipo_reporte_empresa,
                menu_seccion_active: true),
            $this->menu_item(menu_item_titulo: "Reporte Cliente", link: $this->link_em_anticipo_reporte_cliente,
                menu_seccion_active: true),
            $this->menu_item(menu_item_titulo: "Reporte Empleado", link: $this->link_em_anticipo_reporte_empleado,
                menu_seccion_active: true));

        $this->sidebar['reporte_empresa']['titulo'] = "Reporte Empresa";
        $this->sidebar['reporte_empresa']['stepper_active'] = true;
        $this->sidebar['reporte_empresa']['menu'] = array(
            $this->menu_item(menu_item_titulo: "Alta", link: $this->link_alta,menu_seccion_active: true),
            $this->menu_item(menu_item_titulo: "Reporte Empresa", link: $this->link_em_anticipo_reporte_empresa,
        menu_seccion_active: true, menu_lateral_active: true),
            $this->menu_item(menu_item_titulo: "Reporte Cliente", link: $this->link_em_anticipo_reporte_cliente,
        menu_seccion_active: true),
            $this->menu_item(menu_item_titulo: "Reporte Empleado", link: $this->link_em_anticipo_reporte_empleado,
                menu_seccion_active: true));

        $this->sidebar['reporte_cliente']['titulo'] = "Reporte Cliente";
        $this->sidebar['reporte_cliente']['stepper_active'] = true;
        $this->sidebar['reporte_cliente']['menu'] = array(
            $this->menu_item(menu_item_titulo: "Alta", link: $this->link_alta,menu_seccion_active: true),
            $this->menu_item(menu_item_titulo: "Reporte Empresa", link: $this->link_em_anticipo_reporte_empresa,
        menu_seccion_active: true),
            $this->menu_item(menu_item_titulo: "Reporte Cliente", link: $this->link_em_anticipo_reporte_cliente,
        menu_seccion_active: true, menu_lateral_active: true),
            $this->menu_item(menu_item_titulo: "Reporte Empleado", link: $this->link_em_anticipo_reporte_empleado,
                menu_seccion_active: true));

        $this->sidebar['reporte_empleado']['titulo'] = "Reporte Empleado";
        $this->sidebar['reporte_empleado']['stepper_active'] = true;
        $this->sidebar['reporte_empleado']['menu'] = array(
            $this->menu_item(menu_item_titulo: "Alta", link: $this->link_alta,menu_seccion_active: true),
            $this->menu_item(menu_item_titulo: "Reporte Empresa", link: $this->link_em_anticipo_reporte_empresa,
        menu_seccion_active: true),
            $this->menu_item(menu_item_titulo: "Reporte Cliente", link: $this->link_em_anticipo_reporte_cliente,
        menu_seccion_active: true),
            $this->menu_item(menu_item_titulo: "Reporte Empleado", link: $this->link_em_anticipo_reporte_empleado,
        menu_seccion_active: true, menu_lateral_active: true));

        $this->link_em_anticipo_exportar_cliente = $this->obj_link->link_con_id(accion: "exportar_cliente",link: $this->link,
            registro_id: $this->registro_id,seccion: "em_anticipo");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener link',
                data: $this->link_em_anticipo_exportar_cliente);
            print_r($error);
            exit;
        }
        $this->link_em_anticipo_exportar_empresa = $this->obj_link->link_con_id(accion: "exportar_empresa",link: $this->link,
            registro_id: $this->registro_id,seccion: "em_anticipo");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener link',
                data: $this->link_em_anticipo_exportar_empresa);
            print_r($error);
            exit;
        }

    }

    public function exportar_cliente(bool $header, bool $ws = false): array|stdClass
    {
        $com_sucursal_id = -1;
        $em_tipo_anticipo_id = -1;
        $fecha_inicio = date('Y-m-d');
        $fecha_fin = date('Y-m-d');

        if (isset($_POST['com_sucursal_id'])){
            $com_sucursal_id = $_POST['com_sucursal_id'];
        }

        if (isset($_POST['em_tipo_anticipo_id'])){
            $em_tipo_anticipo_id = $_POST['em_tipo_anticipo_id'];
        }

        if (isset($_POST['fecha_inicio'])){
            $fecha_inicio = $_POST['fecha_inicio'];
        }

        if (isset($_POST['fecha_final'])){
            $fecha_fin = $_POST['fecha_final'];
        }

        $filtro['com_sucursal_id'] = $com_sucursal_id;
        $tg_empleado_sucursal = (new tg_empleado_sucursal($this->link))->filtro_and(filtro: $filtro);
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al obtener registros',data:  $tg_empleado_sucursal);
            print_r($error);
            die('Error');
        }

        $data_exportar = array();

        foreach ($tg_empleado_sucursal->registros as $registro){
            $filtro_anticipo['em_empleado.id'] = $registro['em_empleado_id'];
            $filtro_anticipo['em_tipo_anticipo.id'] = $em_tipo_anticipo_id;
            $filtro_especial[0][$fecha_fin]['operador'] = '>=';
            $filtro_especial[0][$fecha_fin]['valor'] = 'em_anticipo.fecha_prestacion';
            $filtro_especial[0][$fecha_fin]['comparacion'] = 'AND';
            $filtro_especial[0][$fecha_fin]['valor_es_campo'] = true;

            $filtro_especial[1][$fecha_inicio]['operador'] = '<=';
            $filtro_especial[1][$fecha_inicio]['valor'] = 'em_anticipo.fecha_prestacion';
            $filtro_especial[1][$fecha_inicio]['comparacion'] = 'AND';
            $filtro_especial[1][$fecha_inicio]['valor_es_campo'] = true;

            $data = (new em_anticipo($this->link))->filtro_and(filtro: $filtro_anticipo, filtro_especial: $filtro_especial);
            if(errores::$error){
                $error = $this->errores->error(mensaje: 'Error al obtener registros',data:  $data);
                print_r($error);
                die('Error');
            }

            if ($data->n_registros >0){
                $data->registros['com_sucursal'] = $registro['com_sucursal_descripcion'];
                $data_exportar[] = $data->registros;
            }
        }

        $exportador = (new exportador());
        $registros_xls = array();

        foreach ($data_exportar as $anticipos){
                foreach ($anticipos as $anticipo){
                    if (is_array($anticipo)){
                        $row = array();
                        $row["nss"] = $anticipo['em_empleado_nss'];
                        $row["codigo_remunerado"] = $anticipo['em_empleado_codigo'];
                        $row["nombre_remunerado"] = $anticipo['em_empleado_nombre'];
                        $row["nombre_remunerado"] .= " ".$anticipo['em_empleado_ap'];
                        $row["nombre_remunerado"] .= " ".$anticipo['em_empleado_am'];
                        $row["razon_social"] = $anticipos['com_sucursal'];
                        $row["concepto"] = $anticipo['em_anticipo_descripcion'];
                        $row["monto_del_anticipo"] = $anticipo['em_anticipo_monto'];
                        $row["tipo_descuento_monto"] = $anticipo['em_tipo_descuento_monto'];
                        $row["sumatoria_de_abonos"] = $anticipo['total_abonado'];
                        $row["saldo"]  = $anticipo['em_anticipo_saldo'];
                        $registros_xls[] = $row;
                    }

                }

        }

        $keys = array();

        foreach (array_keys($registros_xls[0]) as $key) {
            $keys[$key] = strtoupper(str_replace('_', ' ', $key));
        }

        $registros = array();

        foreach ($registros_xls as $row) {
            $registros[] = array_combine(preg_replace(array_map(function($s){return "/^$s$/";},
                array_keys($keys)),$keys, array_keys($row)), $row);
        }

        $resultado = $exportador->listado_base_xls(header: $header, name: $this->seccion, keys:  $keys,
            path_base: $this->path_base,registros:  $registros,totales:  array());
        if(errores::$error){
            $error =  $this->errores->error('Error al generar xls',$resultado);
            if(!$header){
                return $error;
            }
            print_r($error);
            die('Error');
        }

        header('Location:' . $this->link_lista);
        exit;
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
