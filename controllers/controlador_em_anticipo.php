<?php

namespace tglobally\tg_empleado\controllers;

use base\controller\controler;
use gamboamartin\administrador\models\adm_usuario;
use gamboamartin\comercial\models\com_sucursal;
use gamboamartin\empleado\models\em_anticipo;
use gamboamartin\empleado\models\em_tipo_anticipo;
use gamboamartin\errores\errores;
use gamboamartin\organigrama\models\org_sucursal;
use gamboamartin\plugins\exportador;
use IntlDateFormatter;
use PDO;
use stdClass;
use tglobally\template_tg\html;
use tglobally\tg_empleado\models\tg_empleado_sucursal;

class controlador_em_anticipo extends \gamboamartin\empleado\controllers\controlador_em_anticipo
{
    public string $link_em_anticipo_reporte_ejecutivo = '';
    public string $link_em_anticipo_reporte_cliente = '';
    public string $link_em_anticipo_reporte_empresa = '';
    public string $link_em_anticipo_reporte_empleado = '';
    public string $link_em_anticipo_exportar_ejecutivo = '';
    public string $link_em_anticipo_exportar_cliente = '';
    public string $link_em_anticipo_exportar_empresa = '';
    public string $link_em_anticipo_exportar_empleado = '';

    public string $link_em_anticipo_importar_anticipos = '';

    public function __construct(PDO $link, stdClass $paths_conf = new stdClass())
    {
        $html_base = new html();
        parent::__construct(link: $link, html: $html_base);

        $sidebar = $this->init_sidebar();
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al inicializar sidebar', data: $sidebar);
            print_r($error);
            die('Error');
        }
    }

    protected function init_configuraciones(): controler
    {
        $conf = parent::init_configuraciones();

        $this->asignar_propiedad(identificador: 'adm_usuario_id', propiedades: ["label" => "Ejecutivo", "cols" => 12,
            'required' => false, 'disabled' => true, "id_selected" => $this->datos_session_usuario['adm_usuario_id'],
            "filtro" => array("adm_usuario.id" => $this->datos_session_usuario['adm_usuario_id'])]);

        $this->asignar_propiedad(identificador: 'org_sucursal_id', propiedades: ["label" => "Empresa", "cols" => 12,
            "required" => false]);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al asignar propiedad', data: $this);
            print_r($error);
            die('Error');
        }

        $this->asignar_propiedad(identificador: 'com_sucursal_id', propiedades: ["label" => "Cliente", "cols" => 12,
            "required" => true]);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al asignar propiedad', data: $this);
            print_r($error);
            die('Error');
        }

        $this->asignar_propiedad(identificador: 'em_empleado_id', propiedades: ["label" => "Empleado", "cols" => 12,
            "required" => false]);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al asignar propiedad', data: $this);
            print_r($error);
            die('Error');
        }

        $this->asignar_propiedad(identificador: 'em_tipo_anticipo_id', propiedades: ["label" => "Tipo Anticipo",
            "cols" => 12, 'required' => false]);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al asignar propiedad', data: $this);
            print_r($error);
            die('Error');
        }

        $this->asignar_propiedad(identificador: 'fecha_inicio', propiedades: ["place_holder" => "Fecha Inicio",
            'required' => false]);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al asignar propiedad', data: $this);
            print_r($error);
            die('Error');
        }

        $this->asignar_propiedad(identificador: 'fecha_final', propiedades: ["place_holder" => "Fecha Final",
            date(format: 'Y-m-d'), 'required' => false]);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al asignar propiedad', data: $this);
            print_r($error);
            die('Error');
        }

        return $conf;
    }

    protected function init_links(): array|string
    {
        $link = parent::init_links();
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al inicializar links', data: $link);
            print_r($error);
            exit;
        }

        $link = $this->obj_link->get_link(seccion: "em_anticipo", accion: "sube_archivo");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al recuperar link sube_archivo', data: $link);
            print_r($error);
            exit;
        }
        $this->link_em_anticipo_importar_anticipos = $link;

        $link = $this->obj_link->get_link(seccion: "em_anticipo", accion: "reporte_ejecutivo");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al recuperar link reporte_ejecutivo', data: $link);
            print_r($error);
            exit;
        }
        $this->link_em_anticipo_reporte_ejecutivo = $link;

        $link = $this->obj_link->get_link(seccion: "em_anticipo", accion: "reporte_cliente");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al recuperar link reporte_cliente', data: $link);
            print_r($error);
            exit;
        }
        $this->link_em_anticipo_reporte_cliente = $link;

        $link = $this->obj_link->get_link(seccion: "em_anticipo", accion: "reporte_empresa");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al recuperar link reporte_empleado', data: $link);
            print_r($error);
            exit;
        }
        $this->link_em_anticipo_reporte_empresa = $link;

        $link = $this->obj_link->get_link(seccion: "em_anticipo", accion: "reporte_empleado");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al recuperar link reporte_empleado', data: $link);
            print_r($error);
            exit;
        }
        $this->link_em_anticipo_reporte_empleado = $link;

        $link = $this->obj_link->get_link(seccion: "em_anticipo", accion: "exportar_ejecutivo");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al recuperar link exportar_ejecutivo', data: $link);
            print_r($error);
            exit;
        }
        $this->link_em_anticipo_exportar_ejecutivo = $link;

        $link = $this->obj_link->get_link(seccion: "em_anticipo", accion: "exportar_cliente");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al recuperar link exportar_cliente', data: $link);
            print_r($error);
            exit;
        }
        $this->link_em_anticipo_exportar_cliente = $link;

        $link = $this->obj_link->get_link(seccion: "em_anticipo", accion: "exportar_empresa");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al recuperar link exportar_empresa', data: $link);
            print_r($error);
            exit;
        }
        $this->link_em_anticipo_exportar_empresa = $link;

        $link = $this->obj_link->get_link(seccion: "em_anticipo", accion: "exportar_empleado");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al recuperar link exportar_empleado', data: $link);
            print_r($error);
            exit;
        }
        $this->link_em_anticipo_exportar_empleado = $link;

        return $link;
    }

    public function init_selects_inputs(): array
    {
        $keys_selects = parent::init_selects_inputs();
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al icializar selects', data: $keys_selects);
        }

        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "com_sucursal_id", label: "Cliente",
            cols: 12);

        return $keys_selects;
    }

    private function init_sidebar(): stdClass|array
    {
        $menu_items = new stdClass();

        $menu_items->lista = $this->menu_item(menu_item_titulo: "Inicio", link: $this->link_lista);
        $menu_items->alta = $this->menu_item(menu_item_titulo: "Alta", link: $this->link_alta);
        $menu_items->modifica = $this->menu_item(menu_item_titulo: "Modifica", link: $this->link_modifica);
        $menu_items->importar = $this->menu_item(menu_item_titulo: "Importar Anticipos", link: $this->link_em_anticipo_importar_anticipos);
        $menu_items->reportes = $this->menu_item(menu_item_titulo: "Reportes", link: $this->link_em_anticipo_reporte_empleado);
        $menu_items->reporte_ejecutivo = $this->menu_item(menu_item_titulo: "Reporte por Ejecutivo", link: $this->link_em_anticipo_reporte_ejecutivo);
        $menu_items->reporte_empresa = $this->menu_item(menu_item_titulo: "Reporte por Empresa", link: $this->link_em_anticipo_reporte_empresa);
        $menu_items->reporte_cliente = $this->menu_item(menu_item_titulo: "Reporte por Cliente", link: $this->link_em_anticipo_reporte_cliente);
        $menu_items->reporte_trabajador = $this->menu_item(menu_item_titulo: "Reporte por Empleado", link: $this->link_em_anticipo_reporte_empleado);

        $menu_items->lista['menu_seccion_active'] = true;
        $menu_items->lista['menu_lateral_active'] = true;
        $menu_items->alta['menu_seccion_active'] = true;
        $menu_items->alta['menu_lateral_active'] = true;
        $menu_items->modifica['menu_lateral_active'] = true;
        $menu_items->importar['menu_seccion_active'] = true;
        $menu_items->importar['menu_lateral_active'] = true;
        $menu_items->reportes['menu_seccion_active'] = true;
        $menu_items->reportes['menu_lateral_active'] = true;
        $menu_items->reporte_ejecutivo['menu_seccion_active'] = true;
        $menu_items->reporte_cliente['menu_seccion_active'] = true;
        $menu_items->reporte_empresa['menu_seccion_active'] = true;
        $menu_items->reporte_trabajador['menu_seccion_active'] = true;

        $this->sidebar['lista']['titulo'] = "Anticipos";
        $this->sidebar['lista']['menu'] = array($menu_items->alta, $menu_items->importar, $menu_items->reportes);

        $this->sidebar['abono']['titulo'] = "Anticipos - Abonos";
        $this->sidebar['abono']['menu'] = array();

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

        $menu_items->lista['menu_lateral_active'] = false;

        $this->sidebar['reporte_ejecutivo']['titulo'] = "Reportes";
        $this->sidebar['reporte_ejecutivo']['stepper_active'] = true;
        $this->sidebar['reporte_ejecutivo']['menu'] = array($menu_items->lista, $menu_items->reporte_ejecutivo,
            $menu_items->reporte_cliente, $menu_items->reporte_empresa, $menu_items->reporte_trabajador);
        $this->sidebar['reporte_ejecutivo']['menu'][1]['menu_lateral_active'] = true;
        $this->sidebar['reporte_ejecutivo']['menu'][1]['menu_seccion_active'] = false;

        $this->sidebar['reporte_cliente']['titulo'] = "Reportes";
        $this->sidebar['reporte_cliente']['stepper_active'] = true;
        $this->sidebar['reporte_cliente']['menu'] = array($menu_items->lista, $menu_items->reporte_ejecutivo,
            $menu_items->reporte_cliente, $menu_items->reporte_empresa, $menu_items->reporte_trabajador);
        $this->sidebar['reporte_cliente']['menu'][2]['menu_lateral_active'] = true;
        $this->sidebar['reporte_cliente']['menu'][2]['menu_seccion_active'] = false;

        $this->sidebar['reporte_empresa']['titulo'] = "Reportes";
        $this->sidebar['reporte_empresa']['stepper_active'] = true;
        $this->sidebar['reporte_empresa']['menu'] = array($menu_items->lista, $menu_items->reporte_ejecutivo,
            $menu_items->reporte_cliente, $menu_items->reporte_empresa, $menu_items->reporte_trabajador);
        $this->sidebar['reporte_empresa']['menu'][3]['menu_lateral_active'] = true;
        $this->sidebar['reporte_empresa']['menu'][3]['menu_seccion_active'] = false;

        $this->sidebar['reporte_empleado']['titulo'] = "Reportes";
        $this->sidebar['reporte_empleado']['stepper_active'] = true;
        $this->sidebar['reporte_empleado']['menu'] = array($menu_items->lista, $menu_items->reporte_ejecutivo,
            $menu_items->reporte_cliente, $menu_items->reporte_empresa, $menu_items->reporte_trabajador);
        $this->sidebar['reporte_empleado']['menu'][4]['menu_lateral_active'] = true;
        $this->sidebar['reporte_empleado']['menu'][4]['menu_seccion_active'] = false;

        return $menu_items;
    }

    protected function key_selects_txt(array $keys_selects): array
    {
        $keys_selects = parent::key_selects_txt($keys_selects);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al icializar selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6, key: 'fecha_inicio',
            keys_selects: $keys_selects, place_holder: 'Fecha Inicio', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6, key: 'fecha_final',
            keys_selects: $keys_selects, place_holder: 'Fecha Final', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        return $keys_selects;
    }

    private function get_filtros(array $post)
    {
        $filtros = new stdClass();

        $filtros->fecha_inicio = date('1970-01-01');
        $filtros->fecha_final = date('Y-m-d');

        if (isset($post['org_sucursal_id'])) {
            $filtros->org_sucursal_id = $post['org_sucursal_id'];
        }

        if (isset($post['com_sucursal_id'])) {
            $filtros->com_sucursal_id = $post['com_sucursal_id'];
        }

        if (isset($post['em_empleado_id'])) {
            $filtros->em_empleado_id = $post['em_empleado_id'];
        }

        if (isset($this->datos_session_usuario['adm_usuario_id'])) {
            $filtros->adm_usuario_id = $this->datos_session_usuario['adm_usuario_id'];
        }

        if (isset($post['em_tipo_anticipo_id'])) {
            $filtros->em_tipo_anticipo_id = $post['em_tipo_anticipo_id'];
        }

        if (isset($post['fecha_inicio']) && !empty($post['fecha_inicio'])) {
            $filtros->fecha_inicio = $post['fecha_inicio'];
        }

        if (isset($post['fecha_final']) && !empty($post['fecha_final'])) {
            $filtros->fecha_final = $post['fecha_final'];
        }

        return $filtros;
    }

    public function monto_capturado(array $anticipos): float
    {
        return 2.0;
    }

    public function suma_descuentos(array $anticipos): float
    {
        return 1.0;
    }

    private function get_datos_remunerado(int $com_sucursal_id, int $em_empleado_id, array $anticipos): array
    {

        $filtro['com_sucursal_id'] = $com_sucursal_id;
        $filtro['em_empleado_id'] = $em_empleado_id;
        $em_empleado = (new tg_empleado_sucursal($this->link))->filtro_and(filtro: $filtro, limit: 1);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener datos del empleado', data: $em_empleado);
            print_r($error);
            die('Error');
        }

        if ($em_empleado->n_registros <= 0) {
            $error = $this->errores->error(mensaje: 'Error no existe registro relacionado para el cliente|empleado',
                data: $em_empleado);
            print_r($error);
            die('Error');
        }

        $monto_capturado = $this->monto_capturado(anticipos: $anticipos);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener monto capturado', data: $monto_capturado);
            print_r($error);
            die('Error');
        }

        $suma_descuentos = $this->suma_descuentos(anticipos: $anticipos);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener suma descuentos', data: $suma_descuentos);
            print_r($error);
            die('Error');
        }

        $em_empleado = $em_empleado->registros[0];

        $datos['cliente'] = $em_empleado['com_sucursal_descripcion'];
        $datos['id_nombre'] = "XXXX | " . $em_empleado['em_empleado_nombre_completo'];
        $datos['nss'] = $em_empleado['em_empleado_nss'];
        $datos['registro_patronal'] = $em_empleado['em_registro_patronal_descripcion'];
        $datos['monto_capturado'] = $monto_capturado;
        $datos['suma_descuentos'] = $suma_descuentos;
        $datos['saldo_al_dia'] = $monto_capturado - $suma_descuentos;

        return $datos;
    }

    private function get_descuentos_nomina(): array
    {

        return array();
    }

    private function suma_totales(array $registros, array $campo_sumar): stdClass
    {
        $totales = new stdClass();

        foreach ($campo_sumar as $campo) {
            $totales->$campo = 0.0;
        }

        foreach ($registros as $registro) {
            foreach ($campo_sumar as $campo) {
                $valor = $registro[$campo];
                $totales->$campo += $valor;
            }
        }

        return $totales;
    }

    private function maqueta_salida(int $com_sucursal_id, int $em_empleado_id, array $anticipos): array
    {
        $datos_remunerado = $this->get_datos_remunerado(com_sucursal_id: $com_sucursal_id, em_empleado_id: $em_empleado_id,
            anticipos: $anticipos);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener datos del remunerado', data: $datos_remunerado);
            print_r($error);
            die('Error');
        }

        $descuentos_nomina = $this->get_descuentos_nomina();
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener descuentos nomina', data: $descuentos_nomina);
            print_r($error);
            die('Error');
        }

        $tabla_1['title'] = 'DATOS DEL REMUNERADO';
        $tabla_1['orientation'] = 'vertical';
        $tabla_1['headers'] = ['Cliente:', 'ID | Nombre:', 'NSS:', 'Registro patronal:', 'Monto capturado:'
            , 'Suma descuentos:', 'Saldo al día:'];
        $tabla_1['data'] = [
            [
                $datos_remunerado['cliente'],
                $datos_remunerado['id_nombre'],
                $datos_remunerado['nss'],
                $datos_remunerado['registro_patronal'],
                $datos_remunerado['monto_capturado'],
                $datos_remunerado['suma_descuentos'],
                $datos_remunerado['saldo_al_dia']
            ]
        ];
        $tabla_1['startRow'] = 2;
        $tabla_1['startColumn'] = "A";

        $tabla_2['title'] = 'DESCUENTOS APLICADOS POR NÓMINA';
        $tabla_2['headers'] = ['Folio', 'Fecha termino', 'Estatus', 'Estatus', 'Desc. Infonavit', 'Tipo de nómina', 'Ejecutivo'];
        $tabla_2['data'] = [
            ['109926', '01-01-2023', '15-01-2023', 'TIMBRADO', 508.94, 'DUAL', 'Zaida Ivonne'],
            ['109926', '01-01-2023', '15-01-2023', 'TIMBRADO', 508.94, 'DUAL', 'Zaida Ivonne'],
        ];
        $tabla_2['totales'] = array("titulo" => 'SUMA DESCUENTOS:', 'valor' => 1017.88, 'columna' => "G");
        $tabla_2['startRow'] = 2;
        $tabla_2['startColumn'] = "D";

        return array($tabla_1, $tabla_2);
    }

    public function exportar_cliente(bool $header, bool $ws = false): array|stdClass
    {
        $filtro = array();
        $extra_join = array();

        $filtros = $this->get_filtros(post: $_POST);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener filtros', data: $filtros);
            print_r($error);
            die('Error');
        }

        $valida = $this->validacion->valida_existencia_keys(keys: array("com_sucursal_id"),
            registro: $filtros);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al validar el filtros requeridos', data: $valida);
            print_r($error);
            die('Error');
        }

        if (!empty($filtros->com_sucursal_id)) {
            $extra_join["tg_empleado_sucursal"]['key'] = "em_empleado_id";
            $extra_join["tg_empleado_sucursal"]['enlace'] = "em_empleado";
            $extra_join["tg_empleado_sucursal"]['key_enlace'] = "id";
            $extra_join["tg_empleado_sucursal"]['renombre'] = "tg_empleado_sucursal";

            $extra_join["com_sucursal"]['key'] = "id";
            $extra_join["com_sucursal"]['enlace'] = "tg_empleado_sucursal";
            $extra_join["com_sucursal"]['key_enlace'] = "com_sucursal_id";
            $extra_join["com_sucursal"]['renombre'] = "com_sucursal";

            $filtro["tg_empleado_sucursal.com_sucursal_id"] = $filtros->com_sucursal_id;
        }

        $filtro_cliente['tg_empleado_sucursal.com_sucursal_id'] = $filtros->com_sucursal_id;
        $cliente = (new tg_empleado_sucursal($this->link))->filtro_and(filtro: $filtro_cliente);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener datos del cliente', data: $cliente);
            print_r($error);
            die('Error');
        }

        if ($cliente->n_registros <= 0) {
            $error = $this->errores->error(mensaje: 'Error no existe un empleado relacionado para la sucursal', data: $cliente);
            print_r($error);
            die('Error');
        }

        $filtro_rango['em_anticipo.fecha_prestacion'] = ['valor1' => $filtros->fecha_inicio, 'valor2' => $filtros->fecha_final];
        $anticipos = (new em_anticipo($this->link))->filtro_and(extra_join: $extra_join, filtro: $filtro, filtro_rango: $filtro_rango);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener registros', data: $anticipos);
            print_r($error);
            die('Error');
        }

        $registros = array();

        foreach ($anticipos->registros as $anticipo) {
            $registro = [
                $anticipo['em_empleado_nss'],
                $anticipo['em_empleado_id'],
                $anticipo['em_empleado_nombre_completo'],
                $anticipo['em_registro_patronal_descripcion'],
                $anticipo['em_tipo_anticipo_descripcion'],
                $anticipo['em_anticipo_monto'],
                $anticipo['em_tipo_descuento_monto'],
                $anticipo['total_abonado'],
                $anticipo['em_anticipo_saldo'],
                $this->datos_session_usuario['adm_usuario_nombre'],
                $anticipo['em_anticipo_fecha_alta'],
                $anticipo['em_anticipo_comentarios'],
                $anticipo['org_sucursal_descripcion']
            ];
            $registros[] = $registro;
        }

        $cliente = $cliente->registros[0]['com_sucursal_descripcion'];

        $formatter = new IntlDateFormatter('es_ES', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
        $filtros->fecha_inicio = $formatter->format(strtotime($filtros->fecha_inicio));
        $filtros->fecha_final = $formatter->format(strtotime($filtros->fecha_final));

        $periodo = "$filtros->fecha_inicio - $filtros->fecha_final";

        $totales = $this->suma_totales(registros: $anticipos->registros, campo_sumar: array('em_anticipo_monto',
            'em_tipo_descuento_monto', 'total_abonado', 'em_anticipo_saldo'));
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener totales', data: $totales);
            print_r($error);
            die('Error');
        }

        $tabla['detalles'] = [
            ["titulo" => 'CLIENTE:', 'valor' => $cliente],
            ["titulo" => 'PERIODO:', 'valor' => $periodo],
            ["titulo" => 'No.Registros:', 'valor' => $anticipos->n_registros]
        ];
        $tabla['headers'] = ['NSS', 'ID', 'NOMBRE', 'REGISTRO PATRONAL', 'CONCEPTO', 'IMPORTE', 'MONTO A DESCONTAR PROPUESTA',
            'PAGOS', 'SALDO', 'EJECUTIVO IMSS', 'FECHA/HORA CAPTURA', 'COMENTARIOS', 'EMPRESA'];
        $tabla['data'] = $registros;
        $tabla['startRow'] = 4;
        $tabla['startColumn'] = "A";
        $tabla['totales'] = [
            ["columna" => 'F', 'valor' => $totales->em_anticipo_monto],
            ["columna" => 'G', 'valor' => $totales->em_tipo_descuento_monto],
            ["columna" => 'H', 'valor' => $totales->total_abonado],
            ["columna" => 'I', 'valor' => $totales->em_anticipo_saldo]
        ];

        $data["REPORTE GENERAL"] = [$tabla];

        $name = $cliente . "_REPORTE DE ANTICIPOS";

        $resultado = (new exportador())->exportar_template(header: $header, path_base: $this->path_base, name: $name, data: $data,
            styles: Reporte_Template::REPORTE_GENERAL);
        if (errores::$error) {
            $error = $this->errores->error('Error al generar xls', $resultado);
            if (!$header) {
                return $error;
            }
            print_r($error);
            die('Error');
        }

        header('Location:' . $this->link_em_anticipo_reporte_cliente);
        exit;
    }

    public function exportar_ejecutivo(bool $header, bool $ws = false): array|stdClass
    {
        $filtro = array();

        $filtros = $this->get_filtros(post: $_POST);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener filtros', data: $filtros);
            print_r($error);
            die('Error');
        }

        $valida = $this->validacion->valida_existencia_keys(keys: array("adm_usuario_id"),
            registro: $filtros);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al validar el filtros requeridos', data: $valida);
            print_r($error);
            die('Error');
        }

        if (!empty($filtros->adm_usuario_id)) {
            $filtro["em_anticipo.usuario_alta_id"] = $filtros->adm_usuario_id;
        }

        if (!empty($filtros->org_sucursal_id)) {
            $filtro["org_sucursal.id"] = $filtros->org_sucursal_id;
        }

        if (!empty($filtros->em_tipo_anticipo_id)) {
            $filtro["em_tipo_anticipo.id"] = $filtros->em_tipo_anticipo_id;
        }

        $extra_join["tg_empleado_sucursal"]['key'] = "em_empleado_id";
        $extra_join["tg_empleado_sucursal"]['enlace'] = "em_empleado";
        $extra_join["tg_empleado_sucursal"]['key_enlace'] = "id";
        $extra_join["tg_empleado_sucursal"]['renombre'] = "tg_empleado_sucursal";

        $extra_join["com_sucursal"]['key'] = "id";
        $extra_join["com_sucursal"]['enlace'] = "tg_empleado_sucursal";
        $extra_join["com_sucursal"]['key_enlace'] = "com_sucursal_id";
        $extra_join["com_sucursal"]['renombre'] = "com_sucursal";

        $filtro_rango['em_anticipo.fecha_prestacion'] = ['valor1' => $filtros->fecha_inicio, 'valor2' => $filtros->fecha_final];

        $anticipos = (new em_anticipo($this->link))->filtro_and(extra_join: $extra_join, filtro: $filtro,
            filtro_rango: $filtro_rango);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener registros', data: $anticipos);
            print_r($error);
            die('Error');
        }

        $registros = array();

        foreach ($anticipos->registros as $anticipo) {
            $registro = [
                $anticipo['em_empleado_nss'],
                $anticipo['em_empleado_id'],
                $anticipo['em_empleado_nombre_completo'],
                $anticipo['em_registro_patronal_descripcion'],
                $anticipo['em_tipo_anticipo_descripcion'],
                $anticipo['em_anticipo_monto'],
                $anticipo['em_tipo_descuento_monto'],
                $anticipo['total_abonado'],
                $anticipo['em_anticipo_saldo'],
                $anticipo['org_sucursal_descripcion'],
                $anticipo['em_anticipo_fecha_alta'],
                $anticipo['em_anticipo_comentarios'],
                $anticipo['com_sucursal_descripcion']
            ];
            $registros[] = $registro;
        }

        $ejecutivo = $this->datos_session_usuario['adm_usuario_nombre'] . " ";
        $ejecutivo .= $this->datos_session_usuario['adm_usuario_ap'];

        $formatter = new IntlDateFormatter('es_ES', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
        $filtros->fecha_inicio = $formatter->format(strtotime($filtros->fecha_inicio));
        $filtros->fecha_final = $formatter->format(strtotime($filtros->fecha_final));

        $periodo = "$filtros->fecha_inicio - $filtros->fecha_final";

        $totales = $this->suma_totales(registros: $anticipos->registros, campo_sumar: array('em_anticipo_monto',
            'em_tipo_descuento_monto', 'total_abonado', 'em_anticipo_saldo'));
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener totales', data: $totales);
            print_r($error);
            die('Error');
        }

        $tabla['detalles'] = [
            ["titulo" => 'EJECUTIVO:', 'valor' => $ejecutivo],
            ["titulo" => 'PERIODO:', 'valor' => $periodo],
            ["titulo" => 'No.Registros:', 'valor' => $anticipos->n_registros]
        ];
        $tabla['headers'] = ['NSS', 'ID', 'NOMBRE', 'REGISTRO PATRONAL', 'CONCEPTO', 'IMPORTE', 'MONTO A DESCONTAR PROPUESTA',
            'PAGOS', 'SALDO', 'EMPRESA', 'FECHA/HORA CAPTURA', 'COMENTARIOS', 'CLIENTE'];
        $tabla['data'] = $registros;
        $tabla['startRow'] = 4;
        $tabla['startColumn'] = "A";
        $tabla['totales'] = [
            ["columna" => 'F', 'valor' => $totales->em_anticipo_monto],
            ["columna" => 'G', 'valor' => $totales->em_tipo_descuento_monto],
            ["columna" => 'H', 'valor' => $totales->total_abonado],
            ["columna" => 'I', 'valor' => $totales->em_anticipo_saldo]
        ];

        $data["REPORTE GENERAL"] = [$tabla];

        $name = $ejecutivo . "_REPORTE DE ANTICIPOS";

        $resultado = (new exportador())->exportar_template(header: $header, path_base: $this->path_base, name: $name,
            data: $data, styles: Reporte_Template::REPORTE_GENERAL);
        if (errores::$error) {
            $error = $this->errores->error('Error al generar xls', $resultado);
            if (!$header) {
                return $error;
            }
            print_r($error);
            die('Error');
        }

        header('Location:' . $this->link_em_anticipo_reporte_ejecutivo);
        exit;
    }

    public function exportar_empleado(bool $header, bool $ws = false): array|stdClass
    {
        $exportador = (new exportador());

        $filtro = array();
        $filtro_especial = array();

        $index = 0;

        $filtros = $this->get_filtros(post: $_POST);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener filtros', data: $filtros);
            print_r($error);
            die('Error');
        }

        $valida = $this->validacion->valida_existencia_keys(keys: array("com_sucursal_id", "em_empleado_id"),
            registro: $filtros);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al validar el filtros requeridos', data: $valida);
            print_r($error);
            die('Error');
        }

        $em_empleado = (new tg_empleado_sucursal($this->link))->registro(registro_id: $filtros->em_empleado_id);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener datos del empleado', data: $em_empleado);
            print_r($error);
            die('Error');
        }

        if (!empty($filtros->em_empleado_id)) {
            $filtro["em_empleado.id"] = $em_empleado['em_empleado_id'];
        }

        if (!empty($filtros->fecha_inicio)) {
            $filtro_especial[$index][$filtros->fecha_final]['operador'] = '>=';
            $filtro_especial[$index][$filtros->fecha_final]['valor'] = 'em_anticipo.fecha_prestacion';
            $filtro_especial[$index][$filtros->fecha_final]['comparacion'] = 'AND';
            $filtro_especial[$index][$filtros->fecha_final]['valor_es_campo'] = true;
            $index += 1;
        }

        if (!empty($filtros->fecha_final)) {
            $filtro_especial[$index][$filtros->fecha_inicio]['operador'] = '<=';
            $filtro_especial[$index][$filtros->fecha_inicio]['valor'] = 'em_anticipo.fecha_prestacion';
            $filtro_especial[$index][$filtros->fecha_inicio]['comparacion'] = 'AND';
            $filtro_especial[$index][$filtros->fecha_inicio]['valor_es_campo'] = true;
        }

        $tipos_anticipos = (new em_tipo_anticipo($this->link))->get_tipo_anticipos(em_empleado_id: $em_empleado['em_empleado_id']);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener los tipos de anticipo del empleado',
                data: $tipos_anticipos);
            print_r($error);
            die('Error');
        }

        $data = array();

        foreach ($tipos_anticipos->registros as $tipo_anticipo) {
            $filtro["em_anticipo.em_tipo_anticipo_id"] = $tipo_anticipo['em_tipo_anticipo_id'];

            $anticipos = (new em_anticipo($this->link))->filtro_and(filtro: $filtro, filtro_especial: $filtro_especial);
            if (errores::$error) {
                $error = $this->errores->error(mensaje: 'Error al obtener registros', data: $anticipos);
                print_r($error);
                die('Error');
            }

            if ($anticipos->n_registros > 0) {
                $data[$tipo_anticipo['em_tipo_anticipo_descripcion']] = $this->maqueta_salida(com_sucursal_id: $filtros->com_sucursal_id,
                    em_empleado_id: $em_empleado['em_empleado_id'], anticipos: $anticipos->registros);
                if (errores::$error) {
                    $error = $this->errores->error(mensaje: 'Error al maquetar salida de datos', data: $data);
                    print_r($error);
                    die('Error');
                }
            }
        }

        $name = $em_empleado['em_empleado_nombre_completo'] . "_REPORTE POR TRABAJADOR";

        $resultado = $exportador->exportar_template(header: $header, path_base: $this->path_base, name: $name, data: $data,
            styles: Reporte_Template::REPORTE_EMPLEADOS);
        if (errores::$error) {
            $error = $this->errores->error('Error al generar xls', $resultado);
            if (!$header) {
                return $error;
            }
            print_r($error);
            die('Error');
        }

        header('Location:' . $this->link_em_anticipo_reporte_empleado);
        exit;
    }

    public function exportar_empresa(bool $header, bool $ws = false): array|stdClass
    {
        $filtro = array();

        $filtros = $this->get_filtros(post: $_POST);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener filtros', data: $filtros);
            print_r($error);
            die('Error');
        }

        $valida = $this->validacion->valida_existencia_keys(keys: array("org_sucursal_id"),
            registro: $filtros);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al validar el filtros requeridos', data: $valida);
            print_r($error);
            die('Error');
        }

        if (!empty($filtros->org_sucursal_id)) {
            $filtro["org_sucursal.id"] = $filtros->org_sucursal_id;
        }

        if (!empty($filtros->em_tipo_anticipo_id)) {
            $filtro["em_tipo_anticipo.id"] = $filtros->em_tipo_anticipo_id;
        }

        $empresa = (new org_sucursal($this->link))->registro(registro_id: $filtros->org_sucursal_id,
            columnas: array("org_sucursal_descripcion"));
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener registros de empresa', data: $empresa);
            print_r($error);
            die('Error');
        }

        $extra_join["tg_empleado_sucursal"]['key'] = "em_empleado_id";
        $extra_join["tg_empleado_sucursal"]['enlace'] = "em_empleado";
        $extra_join["tg_empleado_sucursal"]['key_enlace'] = "id";
        $extra_join["tg_empleado_sucursal"]['renombre'] = "tg_empleado_sucursal";

        $extra_join["com_sucursal"]['key'] = "id";
        $extra_join["com_sucursal"]['enlace'] = "tg_empleado_sucursal";
        $extra_join["com_sucursal"]['key_enlace'] = "com_sucursal_id";
        $extra_join["com_sucursal"]['renombre'] = "com_sucursal";

        $filtro_rango['em_anticipo.fecha_prestacion'] = ['valor1' => $filtros->fecha_inicio, 'valor2' => $filtros->fecha_final];

        $anticipos = (new em_anticipo($this->link))->filtro_and(extra_join: $extra_join, filtro: $filtro,
            filtro_rango: $filtro_rango);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener registros', data: $anticipos);
            print_r($error);
            die('Error');
        }

        $registros = array();

        foreach ($anticipos->registros as $anticipo) {
            $registro = [
                $anticipo['em_empleado_nss'],
                $anticipo['em_empleado_id'],
                $anticipo['em_empleado_nombre_completo'],
                $anticipo['em_registro_patronal_descripcion'],
                $anticipo['em_tipo_anticipo_descripcion'],
                $anticipo['em_anticipo_monto'],
                $anticipo['em_tipo_descuento_monto'],
                $anticipo['total_abonado'],
                $anticipo['em_anticipo_saldo'],
                $this->datos_session_usuario['adm_usuario_nombre'],
                $anticipo['em_anticipo_fecha_alta'],
                $anticipo['em_anticipo_comentarios'],
                $anticipo['com_sucursal_descripcion']
            ];
            $registros[] = $registro;
        }

        $empresa = $empresa['org_sucursal_descripcion'];

        $formatter = new IntlDateFormatter('es_ES', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
        $filtros->fecha_inicio = $formatter->format(strtotime($filtros->fecha_inicio));
        $filtros->fecha_final = $formatter->format(strtotime($filtros->fecha_final));

        $periodo = "$filtros->fecha_inicio - $filtros->fecha_final";

        $totales = $this->suma_totales(registros: $anticipos->registros, campo_sumar: array('em_anticipo_monto',
            'em_tipo_descuento_monto', 'total_abonado', 'em_anticipo_saldo'));
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener totales', data: $totales);
            print_r($error);
            die('Error');
        }

        $tabla['detalles'] = [
            ["titulo" => 'EMPRESA:', 'valor' => $empresa],
            ["titulo" => 'PERIODO:', 'valor' => $periodo],
            ["titulo" => 'No.Registros:', 'valor' => $anticipos->n_registros]
        ];
        $tabla['headers'] = ['NSS', 'ID', 'NOMBRE', 'REGISTRO PATRONAL', 'CONCEPTO', 'IMPORTE', 'MONTO A DESCONTAR PROPUESTA',
            'PAGOS', 'SALDO', 'EJECUTIVO IMSS', 'FECHA/HORA CAPTURA', 'COMENTARIOS', 'CLIENTE'];
        $tabla['data'] = $registros;
        $tabla['startRow'] = 4;
        $tabla['startColumn'] = "A";
        $tabla['totales'] = [
            ["columna" => 'F', 'valor' => $totales->em_anticipo_monto],
            ["columna" => 'G', 'valor' => $totales->em_tipo_descuento_monto],
            ["columna" => 'H', 'valor' => $totales->total_abonado],
            ["columna" => 'I', 'valor' => $totales->em_anticipo_saldo]
        ];

        $data["REPORTE GENERAL"] = [$tabla];

        $name = $empresa . "_REPORTE DE ANTICIPOS";

        $resultado = (new exportador())->exportar_template(header: $header, path_base: $this->path_base, name: $name, data: $data,
            styles: Reporte_Template::REPORTE_GENERAL);
        if (errores::$error) {
            $error = $this->errores->error('Error al generar xls', $resultado);
            if (!$header) {
                return $error;
            }
            print_r($error);
            die('Error');
        }

        header('Location:' . $this->link_em_anticipo_reporte_empresa);
        exit;
    }

    public function menu_item(string $menu_item_titulo, string $link, bool $menu_seccion_active = false,
                              bool   $menu_lateral_active = false): array
    {
        $menu_item = array();
        $menu_item['menu_item'] = $menu_item_titulo;
        $menu_item['menu_seccion_active'] = $menu_seccion_active;
        $menu_item['link'] = $link;
        $menu_item['menu_lateral_active'] = $menu_lateral_active;

        return $menu_item;
    }

    public function reporte_cliente(bool $header, bool $ws = false)
    {
        $r_alta = parent::alta(header: false);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar template', data: $r_alta, header: $header, ws: $ws);
        }

        $this->modelo->campos_view['com_sucursal_id'] = array("type" => "selects", "model" => new com_sucursal($this->link));
        $this->modelo->campos_view['fecha_inicio'] = array("type" => "inputs");
        $this->modelo->campos_view['fecha_final'] = array("type" => "inputs");

        $inputs = $this->genera_inputs(keys_selects: $this->keys_selects);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al generar inputs', data: $inputs);
            print_r($error);
            die('Error');
        }

        return $this->inputs;
    }

    public function reporte_ejecutivo(bool $header, bool $ws = false)
    {
        $r_alta = parent::alta(header: false);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar template', data: $r_alta, header: $header, ws: $ws);
        }

        $this->modelo->campos_view['adm_usuario_id'] = array("type" => "selects", "model" => new adm_usuario($this->link));
        $this->modelo->campos_view['org_sucursal_id'] = array("type" => "selects", "model" => new org_sucursal($this->link));
        $this->modelo->campos_view['fecha_inicio'] = array("type" => "inputs");
        $this->modelo->campos_view['fecha_final'] = array("type" => "inputs");

        $inputs = $this->genera_inputs(keys_selects: $this->keys_selects);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al generar inputs', data: $inputs);
            print_r($error);
            die('Error');
        }

        return $this->inputs;
    }

    public function reporte_empresa(bool $header, bool $ws = false)
    {
        $r_alta = parent::alta(header: false);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar template', data: $r_alta, header: $header, ws: $ws);
        }

        $this->modelo->campos_view['org_sucursal_id'] = array("type" => "selects", "model" => new org_sucursal($this->link));
        $this->modelo->campos_view['fecha_inicio'] = array("type" => "inputs");
        $this->modelo->campos_view['fecha_final'] = array("type" => "inputs");

        $this->asignar_propiedad(identificador: 'org_sucursal_id', propiedades: ["required" => true]);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al asignar propiedad', data: $this);
            print_r($error);
            die('Error');
        }

        $inputs = $this->genera_inputs(keys_selects: $this->keys_selects);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al generar inputs', data: $inputs);
            print_r($error);
            die('Error');
        }

        return $this->inputs;
    }

    public function reporte_empleado(bool $header, bool $ws = false)
    {
        $r_alta = parent::alta(header: false);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar template', data: $r_alta, header: $header, ws: $ws);
        }

        $this->modelo->campos_view['com_sucursal_id'] = array("type" => "selects", "model" => new com_sucursal($this->link));
        $this->modelo->campos_view['fecha_inicio'] = array("type" => "inputs");
        $this->modelo->campos_view['fecha_final'] = array("type" => "inputs");

        $this->asignar_propiedad(identificador: 'em_empleado_id', propiedades: ["required" => true]);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al asignar propiedad', data: $this);
            print_r($error);
            die('Error');
        }

        $inputs = $this->inputs(keys_selects: $this->keys_selects);
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs', data: $inputs, header: $header, ws: $ws);
        }

        return $this->inputs;
    }
}
