<?php

namespace tglobally\tg_empleado\controllers;

use gamboamartin\documento\models\doc_documento;
use gamboamartin\empleado\models\em_anticipo;
use gamboamartin\empleado\models\em_tipo_anticipo;
use gamboamartin\empleado\models\em_tipo_descuento;
use gamboamartin\errores\errores;
use gamboamartin\plugins\exportador;
use PDO;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use stdClass;
use tglobally\template_tg\html;
use tglobally\tg_empleado\models\em_empleado;
use tglobally\tg_empleado\models\tg_empleado_sucursal;

class controlador_em_anticipo extends \gamboamartin\empleado\controllers\controlador_em_anticipo
{

    public string $link_em_anticipo_importar_anticipos = '';
    public string $link_em_anticipo_exportar_cliente = '';
    public string $link_em_anticipo_exportar_empresa = '';

    public function __construct(PDO $link, stdClass $paths_conf = new stdClass())
    {
        $html_base = new html();
        parent::__construct(link: $link, html: $html_base);
        $this->titulo_lista = 'Anticipos';


        $this->link_em_anticipo_importar_anticipos = $this->obj_link->link_con_id(accion: "sube_archivo", link: $this->link,
            registro_id: $this->registro_id, seccion: "em_anticipo");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener link', data: $this->link_em_anticipo_importar_anticipos);
            print_r($error);
            exit;
        }


        $this->link_em_anticipo_exportar_cliente = $this->obj_link->link_con_id(accion: "exportar_cliente", link: $this->link,
            registro_id: $this->registro_id, seccion: "em_anticipo");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener link',
                data: $this->link_em_anticipo_exportar_cliente);
            print_r($error);
            exit;
        }
        $this->link_em_anticipo_exportar_empresa = $this->obj_link->link_con_id(accion: "exportar_empresa", link: $this->link,
            registro_id: $this->registro_id, seccion: "em_anticipo");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener link',
                data: $this->link_em_anticipo_exportar_empresa);
            print_r($error);
            exit;
        }

        $sidebar = $this->init_sidebar();
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al inicializar sidebar', data: $sidebar);
            print_r($error);
            die('Error');
        }

        $this->asignar_propiedad(identificador:'fecha_inicio', propiedades: ["required" => false]);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al asignar propiedad', data: $this);
            print_r($error);
            die('Error');
        }

        $this->asignar_propiedad(identificador:'fecha_final', propiedades: ["required" => false]);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al asignar propiedad', data: $this);
            print_r($error);
            die('Error');
        }

    }

    public function get_datos_excel(string $ruta_absoluta)
    {
        $documento = IOFactory::load($ruta_absoluta);
        $anticipos = array();
        $hojaActual = $documento->getSheet(0);
        $registros = array();
        foreach ($hojaActual->getRowIterator() as $fila) {
            foreach ($fila->getCellIterator() as $celda) {
                $fila = $celda->getRow();
                $valorRaw = $celda->getValue();
                $columna = $celda->getColumn();

                if ($fila >= 2) {
                    if ($columna === "A") {
                        $reg = new stdClass();
                        $reg->fila = $fila;
                        $registros[] = $reg;
                    }
                }
            }
        }

        foreach ($registros as $registro) {
            $reg = new stdClass();
            $reg->nss = $hojaActual->getCell('A' . $registro->fila)->getValue();
            $reg->nombre = $hojaActual->getCell('B' . $registro->fila)->getValue();
            $reg->ap = $hojaActual->getCell('C' . $registro->fila)->getValue();
            $reg->am = $hojaActual->getCell('D' . $registro->fila)->getValue();
            $reg->empresa = $hojaActual->getCell('E' . $registro->fila)->getValue();
            $reg->fecha_inicio = $hojaActual->getCell('F' . $registro->fila)->getCalculatedValue();
            $reg->fecha_inicio = Date::excelToDateTimeObject($reg->fecha_inicio)->format('Y-m-d');
            $reg->fecha_compromiso = $hojaActual->getCell('G' . $registro->fila)->getCalculatedValue();
            $reg->fecha_compromiso = Date::excelToDateTimeObject($reg->fecha_compromiso)->format('Y-m-d');
            $reg->concepto = $hojaActual->getCell('H' . $registro->fila)->getValue();
            $reg->importe = $hojaActual->getCell('I' . $registro->fila)->getValue();
            $reg->descuento_periodo = $hojaActual->getCell('J' . $registro->fila)->getValue();
            $reg->comentarios = $hojaActual->getCell('K' . $registro->fila)->getValue();
            $anticipos[] = $reg;
        }

        return $anticipos;
    }

    private function init_sidebar(): stdClass|array
    {
        $menu_items = new stdClass();

        $menu_items->lista = $this->menu_item(menu_item_titulo: "Inicio", link: $this->link_lista);
        $menu_items->alta = $this->menu_item(menu_item_titulo: "Alta", link: $this->link_alta);
        $menu_items->modifica = $this->menu_item(menu_item_titulo: "Modifica", link: $this->link_modifica);
        $menu_items->importar = $this->menu_item(menu_item_titulo: "Importar Anticipos", link: $this->link_em_anticipo_importar_anticipos);
        $menu_items->reportes = $this->menu_item(menu_item_titulo: "Reportes", link: $this->link_em_anticipo_reporte_cliente);
        $menu_items->reporte_ejecutivo = $this->menu_item(menu_item_titulo: "Reporte por Ejecutivo", link: $this->link_em_anticipo_reporte_empresa);
        $menu_items->reporte_empresa = $this->menu_item(menu_item_titulo: "Reporte por Empresa", link: $this->link_em_anticipo_reporte_empresa);
        $menu_items->reporte_cliente = $this->menu_item(menu_item_titulo: "Reporte por Cliente", link: $this->link_em_anticipo_reporte_empresa);
        $menu_items->reporte_trabajador = $this->menu_item(menu_item_titulo: "Reporte por Trabajador", link: $this->link_em_anticipo_reporte_empresa);

        $menu_items->lista['menu_seccion_active'] = true;
        $menu_items->lista['menu_lateral_active'] = true;
        $menu_items->alta['menu_seccion_active'] = true;
        $menu_items->alta['menu_lateral_active'] = true;
        $menu_items->modifica['menu_lateral_active'] = true;
        $menu_items->importar['menu_seccion_active'] = true;
        $menu_items->importar['menu_lateral_active'] = true;
        $menu_items->reportes['menu_seccion_active'] = true;
        $menu_items->reportes['menu_lateral_active'] = true;

        $this->sidebar['lista']['titulo'] = "Anticipos";
        $this->sidebar['lista']['menu'] = array($menu_items->alta, $menu_items->importar, $menu_items->reportes);

        $menu_items->alta['menu_seccion_active'] = false;

        $this->sidebar['alta']['titulo'] = "Anticipos";
        $this->sidebar['alta']['stepper_active'] = true;
        $this->sidebar['alta']['menu'] = array($menu_items->alta);

        $this->sidebar['modifica']['titulo'] = "Anticipos";
        $this->sidebar['modifica']['stepper_active'] = true;
        $this->sidebar['modifica']['menu'] = array($menu_items->modifica);

        $menu_items->importar['menu_seccion_active'] = false;

        $this->sidebar['sube_archivo']['titulo'] = "Anticipos";
        $this->sidebar['sube_archivo']['stepper_active'] = true;
        $this->sidebar['sube_archivo']['menu'] = array($menu_items->importar);

        $this->sidebar['reporte_empresa']['titulo'] = "Reportes";
        $this->sidebar['reporte_empresa']['stepper_active'] = true;
        $this->sidebar['reporte_empresa']['menu'] = array($menu_items->lista, $menu_items->reporte_ejecutivo);

        $this->sidebar['reporte_cliente']['titulo'] = "Reportes";
        $this->sidebar['reporte_cliente']['stepper_active'] = true;
        $this->sidebar['reporte_cliente']['menu'] = array($menu_items->lista, $menu_items->reporte_cliente);




        $this->sidebar['reporte_empleado']['titulo'] = "Reporte Empleado";
        $this->sidebar['reporte_empleado']['stepper_active'] = true;
        $this->sidebar['reporte_empleado']['menu'] = array(
            $this->menu_item(menu_item_titulo: "Alta", link: $this->link_alta, menu_seccion_active: true),
            $this->menu_item(menu_item_titulo: "Reporte Empresa", link: $this->link_em_anticipo_reporte_empresa,
                menu_seccion_active: true),
            $this->menu_item(menu_item_titulo: "Reporte Cliente", link: $this->link_em_anticipo_reporte_cliente,
                menu_seccion_active: true),
            $this->menu_item(menu_item_titulo: "Reporte Empleado", link: $this->link_em_anticipo_reporte_empleado,
                menu_seccion_active: true, menu_lateral_active: true));

        return $menu_items;
    }

    public function lee_archivo(bool $header, bool $ws = false)
    {
        if (!isset($_FILES['archivo'])) {
            $error = $this->errores->error(mensaje: 'Error no existe archivo', data: $_FILES);
            if (!$header) {
                return $error;
            }
            print_r($error);
            die('Error');
        }

        $doc_documento_modelo = new doc_documento($this->link);
        $doc_documento_modelo->registro['descripcion'] = $_FILES['archivo']['name'];
        $doc_documento_modelo->registro['descripcion_select'] = $_FILES['archivo']['name'];
        $doc_documento_modelo->registro['doc_tipo_documento_id'] = 1;
        $doc_documento = $doc_documento_modelo->alta_bd(file: $_FILES['archivo']);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al dar de alta el documento', data: $doc_documento);
            if (!$header) {
                return $error;
            }
            print_r($error);
            die('Error');
        }

        $datos_excel = $this->get_datos_excel(ruta_absoluta: $doc_documento->registro['doc_documento_ruta_absoluta']);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error obtener datos del archivo excel', data: $datos_excel);
            if (!$header) {
                return $error;
            }
            print_r($error);
            die('Error');
        }

        foreach ($datos_excel as $anticipo) {

            $filtro_empleado = array();
            $filtro_empleado['em_empleado.nss'] = $anticipo->nss;
            $em_empleado = (new em_empleado($this->link))->filtro_and(filtro: $filtro_empleado);
            if (errores::$error) {
                $error = $this->errores->error(mensaje: 'Error obtener empleado', data: $em_empleado);
                if (!$header) {
                    return $error;
                }
                print_r($error);
                die('Error');
            }

            if (!isset($movimiento->nombre) && !isset($movimiento->ap) && !isset($movimiento->am) &&
                $em_empleado->n_registros <= 0) {
                $error = $this->errores->error(mensaje: "Error no existe el NSS: $anticipo->nss", data: $anticipo);
                if (!$header) {
                    return $error;
                }
                print_r($error);
                die('Error');
            } else if (isset($movimiento->nombre) && isset($movimiento->ap) && isset($movimiento->am) &&
                $em_empleado->n_registros <= 0) {
                $filtro_empleado = array();
                $filtro_empleado['em_empleado.nombre'] = $anticipo->nombre;
                $filtro_empleado['em_empleado.ap'] = $anticipo->ap;
                $filtro_empleado['em_empleado.am'] = $anticipo->am;
                $em_empleado = (new em_empleado($this->link))->filtro_and(filtro: $filtro_empleado);
                if (errores::$error) {
                    $error = $this->errores->error(mensaje: 'Error obtener empleado', data: $em_empleado);
                    if (!$header) {
                        return $error;
                    }
                    print_r($error);
                    die('Error');
                }

                if ($em_empleado->n_registros <= 0) {
                    $error = $this->errores->error(mensaje: "Error no existe el empleado: $anticipo->nombre 
                    $anticipo->ap $anticipo->am con NSS $anticipo->nss", data: $anticipo);
                    if (!$header) {
                        return $error;
                    }
                    print_r($error);
                    die('Error');
                }
            }

            $filtro_anticipo['em_tipo_anticipo.descripcion'] = $anticipo->concepto;
            $tipo_anticipo = (new em_tipo_anticipo($this->link))->filtro_and(filtro: $filtro_anticipo);
            if (errores::$error) {
                $error = $this->errores->error(mensaje: 'Error obtener tipo de anticipo', data: $tipo_anticipo);
                if (!$header) {
                    return $error;
                }
                print_r($error);
                die('Error');
            }

            if ($tipo_anticipo->n_registros <= 0) {
                $error = $this->errores->error(mensaje: "Error no existe el tipo de anticipo: $anticipo->concepto",
                    data: $anticipo);
                if (!$header) {
                    return $error;
                }
                print_r($error);
                die('Error');
            }

            $filtro_descuento['em_tipo_descuento.monto'] = $anticipo->descuento_periodo;
            $tipo_descuento = (new em_tipo_descuento($this->link))->filtro_and(filtro: $filtro_descuento);
            if (errores::$error) {
                $error = $this->errores->error(mensaje: 'Error obtener tipo de anticipo', data: $tipo_descuento);
                if (!$header) {
                    return $error;
                }
                print_r($error);
                die('Error');
            }

            if ($tipo_descuento->n_registros <= 0) {
                $error = $this->errores->error(mensaje: "Error no existe el tipo de descuento: $anticipo->descuento_periodo",
                    data: $anticipo);
                if (!$header) {
                    return $error;
                }
                print_r($error);
                die('Error');
            }

            $registro['em_empleado_id'] = $em_empleado->registros[0]['em_empleado_id'];
            $registro['em_tipo_anticipo_id'] = $tipo_anticipo->registros[0]['em_tipo_anticipo_id'];
            $registro['em_tipo_descuento_id'] = $tipo_descuento->registros[0]['em_tipo_descuento_id'];
            $registro['codigo'] = rand() . $anticipo->concepto;
            $registro['descripcion'] = $anticipo->concepto;
            $registro['monto'] = $anticipo->importe;
            $registro['n_pagos'] = 1;
            $registro['fecha_prestacion'] = $anticipo->fecha_compromiso;
            $registro['fecha_inicio_descuento'] = $anticipo->fecha_inicio;
            $registro['comentarios'] = $anticipo->comentarios;

            $alta = (new em_anticipo($this->link))->alta_registro(registro: $registro);
            if (errores::$error) {
                $error = $this->errores->error(mensaje: 'Error al dar de alta anticipo', data: $alta);
                if (!$header) {
                    return $error;
                }
                print_r($error);
                die('Error');
            }
        }

        header('Location:' . $this->link_lista);
        exit;
    }

    public function sube_archivo(bool $header, bool $ws = false)
    {
        $r_alta = parent::alta(header: false, ws: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al generar template', data: $r_alta);
        }

        return $r_alta;
    }

    public function exportar_empresa(bool $header, bool $ws = false): array|stdClass
    {
        $com_sucursal_id = "";
        $em_tipo_anticipo_id = "";
        $fecha_inicio = date('Y-m-d');
        $fecha_fin = date('Y-m-d');

        if (isset($_POST['com_sucursal_id'])) {
            $com_sucursal_id = $_POST['com_sucursal_id'];
        }

        if (isset($_POST['em_tipo_anticipo_id'])) {
            $em_tipo_anticipo_id = $_POST['em_tipo_anticipo_id'];
        }

        if (isset($_POST['fecha_inicio'])) {
            $fecha_inicio = $_POST['fecha_inicio'];
        }

        if (isset($_POST['fecha_final'])) {
            $fecha_fin = $_POST['fecha_final'];
        }

        $filtro['com_sucursal_id'] = $com_sucursal_id;
        $tg_empleado_sucursal = (new tg_empleado_sucursal($this->link))->filtro_and(filtro: $filtro);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener registros', data: $tg_empleado_sucursal);
            print_r($error);
            die('Error');
        }

        $data_exportar = array();

        foreach ($tg_empleado_sucursal->registros as $registro) {
            $filtro_anticipo['em_empleado.id'] = $registro['em_empleado_id'];
            $filtro_especial = array();

            if ($em_tipo_anticipo_id !== "") {
                $filtro_anticipo['em_tipo_anticipo.id'] = $em_tipo_anticipo_id;
            }

            if ($fecha_fin !== "" && $fecha_inicio !== "") {
                $filtro_especial[0][$fecha_fin]['operador'] = '>=';
                $filtro_especial[0][$fecha_fin]['valor'] = 'em_anticipo.fecha_prestacion';
                $filtro_especial[0][$fecha_fin]['comparacion'] = 'AND';
                $filtro_especial[0][$fecha_fin]['valor_es_campo'] = true;

                $filtro_especial[1][$fecha_inicio]['operador'] = '<=';
                $filtro_especial[1][$fecha_inicio]['valor'] = 'em_anticipo.fecha_prestacion';
                $filtro_especial[1][$fecha_inicio]['comparacion'] = 'AND';
                $filtro_especial[1][$fecha_inicio]['valor_es_campo'] = true;
            }

            $data = (new em_anticipo($this->link))->filtro_and(filtro: $filtro_anticipo, filtro_especial: $filtro_especial);
            if (errores::$error) {
                $error = $this->errores->error(mensaje: 'Error al obtener registros', data: $data);
                print_r($error);
                die('Error');
            }

            if ($data->n_registros > 0) {
                $data->registros['com_sucursal'] = $registro['com_sucursal_descripcion'];
                $data_exportar[] = $data->registros;
            }
        }

        $exportador = (new exportador());
        $registros_xls = array();

        foreach ($data_exportar as $anticipos) {
            foreach ($anticipos as $anticipo) {
                if (is_array($anticipo)) {
                    $row = array();
                    $row["nss"] = $anticipo['em_empleado_nss'];
                    $row["codigo_remunerado"] = $anticipo['em_empleado_codigo'];
                    $row["nombre_remunerado"] = $anticipo['em_empleado_nombre'];
                    $row["nombre_remunerado"] .= " " . $anticipo['em_empleado_ap'];
                    $row["nombre_remunerado"] .= " " . $anticipo['em_empleado_am'];
                    $row["razon_social"] = $anticipos['com_sucursal'];
                    $row["concepto"] = $anticipo['em_anticipo_descripcion'];
                    $row["monto_del_anticipo"] = $anticipo['em_anticipo_monto'];
                    $row["tipo_descuento_monto"] = $anticipo['em_tipo_descuento_monto'];
                    $row["sumatoria_de_abonos"] = $anticipo['total_abonado'];
                    $row["saldo"] = $anticipo['em_anticipo_saldo'];
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
            $registros[] = array_combine(preg_replace(array_map(function ($s) {
                return "/^$s$/";
            },
                array_keys($keys)), $keys, array_keys($row)), $row);
        }

        $resultado = $exportador->listado_base_xls(header: $header, name: $this->seccion, keys: $keys,
            path_base: $this->path_base, registros: $registros, totales: array());
        if (errores::$error) {
            $error = $this->errores->error('Error al generar xls', $resultado);
            if (!$header) {
                return $error;
            }
            print_r($error);
            die('Error');
        }

        header('Location:' . $this->link_lista);
        exit;
    }

    public function exportar_cliente(bool $header, bool $ws = false): array|stdClass
    {
        $filtros = new stdClass();
        $anticipos = array();
        $registros = array();
        $salida_excel = array();

        $filtro = array();
        $extra_join = array();
        $filtro_especial = array();

        $index = 0;

        $exportador = (new exportador());


        if (isset($_POST['com_sucursal_id'])) {
            $filtros->com_sucursal_id = $_POST['com_sucursal_id'];
        }

        if (isset($_POST['em_tipo_anticipo_id'])) {
            $filtros->em_tipo_anticipo_id = $_POST['em_tipo_anticipo_id'];
        }

        if (isset($_POST['fecha_inicio'])) {
            $filtros->fecha_inicio = $_POST['fecha_inicio'];
        }

        if (isset($_POST['fecha_final'])) {
            $filtros->fecha_final = $_POST['fecha_final'];
        }

        if (!empty($filtros->com_sucursal_id)){
            $extra_join["tg_empleado_sucursal"]['key'] = "em_empleado_id";
            $extra_join["tg_empleado_sucursal"]['enlace'] = "em_empleado";
            $extra_join["tg_empleado_sucursal"]['key_enlace'] = "id";
            $extra_join["tg_empleado_sucursal"]['renombre'] = "tg_empleado_sucursal";

            $extra_join["com_sucursal"]['key'] = "id";
            $extra_join["com_sucursal"]['enlace'] = "tg_empleado_sucursal";
            $extra_join["com_sucursal"]['key_enlace'] = "com_sucursal_id";
            $extra_join["com_sucursal"]['renombre'] = "com_sucursal";

            $filtro["tg_empleado_sucursal.com_sucursal_id"]  = $filtros->com_sucursal_id;
        }

        if (!empty($filtros->em_tipo_anticipo_id)){
            $filtro["em_tipo_anticipo.id"]  = $filtros->em_tipo_anticipo_id;
        }

        if (!empty($filtros->fecha_inicio)){
            $filtro_especial[$index][$filtros->fecha_final]['operador'] = '>=';
            $filtro_especial[$index][$filtros->fecha_final]['valor'] = 'em_anticipo.fecha_prestacion';
            $filtro_especial[$index][$filtros->fecha_final]['comparacion'] = 'AND';
            $filtro_especial[$index][$filtros->fecha_final]['valor_es_campo'] = true;

            $index += 1;
        }

        if (!empty($filtros->fecha_final)){
            $filtro_especial[$index][$filtros->fecha_inicio]['operador'] = '<=';
            $filtro_especial[$index][$filtros->fecha_inicio]['valor'] = 'em_anticipo.fecha_prestacion';
            $filtro_especial[$index][$filtros->fecha_inicio]['comparacion'] = 'AND';
            $filtro_especial[$index][$filtros->fecha_inicio]['valor_es_campo'] = true;
        }

        $anticipos = (new em_anticipo($this->link))->filtro_and(extra_join: $extra_join,filtro: $filtro,
            filtro_especial: $filtro_especial);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener registros', data: $anticipos);
            print_r($error);
            die('Error');
        }

        foreach ($anticipos->registros as $anticipo) {
            $row = array();
            $row["nss"] = $anticipo['em_empleado_nss'];
            $row["codigo_remunerado"] = $anticipo['em_empleado_codigo'];
            $row["nombre_remunerado"] = $anticipo['em_empleado_nombre'];
            $row["nombre_remunerado"] .= " " . $anticipo['em_empleado_ap'];
            $row["nombre_remunerado"] .= " " . $anticipo['em_empleado_am'];
            $row["razon_social"] = $anticipo['com_sucursal_descripcion'] ?? "";
            $row["concepto"] = $anticipo['em_anticipo_descripcion'];
            $row["monto_del_anticipo"] = $anticipo['em_anticipo_monto'];
            $row["tipo_descuento_monto"] = $anticipo['em_tipo_descuento_monto'];
            $row["sumatoria_de_abonos"] = $anticipo['total_abonado'];
            $row["saldo"] = $anticipo['em_anticipo_saldo'];
            $registros[] = $row;
        }

        $keys = array_reduce($registros, 'array_merge', array());
        $keys_value = array_change_key_case($keys, CASE_UPPER);
        $keys_value = array_keys($keys_value);
        $keys_value = preg_replace("/[^a-zA-Z 0-9]+/", " ",$keys_value);
        $keys = array_combine(array_keys($keys),$keys_value);

        foreach ($registros as $row) {
            $salida_excel[] = array_combine(preg_replace(array_map(function ($s) {
                return "/^$s$/";
            }, array_keys($keys)), $keys, array_keys($row)), $row);
        }

        $resultado = $exportador->listado_base_xls(header: $header, name: $this->seccion, keys: $keys,
            path_base: $this->path_base, registros: $salida_excel, totales: array());
        if (errores::$error) {
            $error = $this->errores->error('Error al generar xls', $resultado);
            if (!$header) {
                return $error;
            }
            print_r($error);
            die('Error');
        }

        header('Location:' . $this->link_lista);
        exit;
    }

    public function menu_item(string $menu_item_titulo, string $link, bool $menu_seccion_active = false,
                              bool $menu_lateral_active = false): array
    {
        $menu_item = array();
        $menu_item['menu_item'] = $menu_item_titulo;
        $menu_item['menu_seccion_active'] = $menu_seccion_active;
        $menu_item['link'] = $link;
        $menu_item['menu_lateral_active'] = $menu_lateral_active;

        return $menu_item;
    }


}
