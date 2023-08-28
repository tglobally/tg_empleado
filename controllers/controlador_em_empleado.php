<?php
namespace tglobally\tg_empleado\controllers;

use gamboamartin\banco\models\bn_sucursal;
use gamboamartin\comercial\models\com_sucursal;
use gamboamartin\documento\models\doc_documento;
use gamboamartin\empleado\models\em_cuenta_bancaria;
use gamboamartin\empleado\models\em_registro_patronal;
use gamboamartin\errores\errores;
use gamboamartin\facturacion\models\fc_csd;
use gamboamartin\organigrama\models\org_sucursal;
use gamboamartin\plugins\Importador;
use gamboamartin\system\actions;
use PhpOffice\PhpSpreadsheet\IOFactory;
use tglobally\tg_empleado\models\em_empleado;
use tglobally\tg_empleado\models\tg_conf_provision;
use tglobally\tg_empleado\models\tg_conf_provisiones_empleado;
use tglobally\tg_empleado\models\tg_empleado_sucursal;
use PDO;
use stdClass;
use tglobally\template_tg\html;
use tglobally\tg_empleado\models\tg_tipo_provision;
use Throwable;

class controlador_em_empleado extends \gamboamartin\empleado\controllers\controlador_em_empleado {
    public controlador_tg_empleado_sucursal $controlador_tg_empleado_sucursal;
    public stdClass $tg_empleado_sucursal;
    public string $link_em_empleado_fiscales = '';
    public string $link_em_empleado_imss = '';
    public string $link_em_empleado_cuenta_bancaria = '';
    public string $link_em_empleado_anticipo = '';
    public string $link_em_empleado_asigna_sucursal = '';
    public string $link_em_empleado_asigna_provision = '';
    public string $link_em_empleado_asigna_percepcion = '';
    public string $link_tg_empleado_sucursal_alta_bd = '';
    public string $link_tg_empleado_asigna_provision_bd = '';
    public string $link_tg_empleado_asigna_percepcion_bd = '';
    public string $link_em_empleado_asigna_correo = '';

    public string $link_dp_pais_alta = '';
    public string $link_dp_municipio_alta = '';
    public string $link_dp_estado_alta = '';
    public string $link_dp_cp_alta = '';
    public string $link_dp_colonia_alta = '';
    public string $link_dp_calle_alta = '';

    public function __construct(PDO $link, stdClass $paths_conf = new stdClass())
    {
        $html_base = new html();
        parent::__construct(link: $link, html: $html_base, paths_conf: $paths_conf);

        $modelo = new em_empleado(link: $link);
        $this->modelo = $modelo;

        $this->controlador_tg_empleado_sucursal = new controlador_tg_empleado_sucursal(
            link: $this->link, paths_conf: $paths_conf);

        $this->link_em_empleado_fiscales = $this->obj_link->link_con_id(accion: "fiscales",link: $this->link,
            registro_id: $this->registro_id,seccion: "em_empleado");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener link',
                data: $this->link_em_empleado_fiscales);
            print_r($error);
            exit;
        }

        $this->link_em_empleado_imss = $this->obj_link->link_con_id(accion: "imss",link: $this->link,
            registro_id: $this->registro_id,seccion: "em_empleado");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener link',
                data: $this->link_em_empleado_imss);
            print_r($error);
            exit;
        }

        $this->link_em_empleado_cuenta_bancaria = $this->obj_link->link_con_id(accion: "cuenta_bancaria",link: $this->link,
            registro_id: $this->registro_id,seccion: "em_empleado");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener link',
                data: $this->link_em_empleado_cuenta_bancaria);
            print_r($error);
            exit;
        }

        $this->link_em_empleado_anticipo = $this->obj_link->link_con_id(accion: "anticipo",link: $this->link,
            registro_id: $this->registro_id,seccion: "em_empleado");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener link',
                data: $this->link_em_empleado_anticipo);
            print_r($error);
            exit;
        }

        $this->link_em_empleado_asigna_sucursal = $this->obj_link->link_con_id(accion: "asigna_sucursal",link: $this->link,
            registro_id: $this->registro_id,seccion: "em_empleado");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener link',
                data: $this->link_em_empleado_asigna_sucursal);
            print_r($error);
            exit;
        }

        $this->link_em_empleado_asigna_provision = $this->obj_link->link_con_id(accion: "asigna_provision",link: $this->link,
            registro_id: $this->registro_id,seccion: "em_empleado");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener link',
                data: $this->link_em_empleado_asigna_provision);
            print_r($error);
            exit;
        }

        $this->link_em_empleado_asigna_percepcion = $this->obj_link->link_con_id(accion: "asigna_percepcion",link: $this->link,
            registro_id: $this->registro_id,seccion: "em_empleado");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener link',
                data: $this->link_em_empleado_asigna_percepcion);
            print_r($error);
            exit;
        }

        $this->link_em_empleado_asigna_correo = $this->obj_link->link_con_id(accion: "asigna_correo",link: $this->link,
            registro_id: $this->registro_id,seccion: "em_empleado");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener link',
                data: $this->link_em_empleado_asigna_correo);
            print_r($error);
            exit;
        }



        $this->link_tg_empleado_sucursal_alta_bd = $this->obj_link->link_alta_bd(link: $this->link,
            seccion: 'tg_empleado_sucursal');
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener link',
                data: $this->link_tg_empleado_sucursal_alta_bd);
            print_r($error);
            exit;
        }

        $this->link_tg_empleado_asigna_provision_bd = $this->obj_link->link_con_id(accion: "asigna_provision_bd",link: $this->link,
            registro_id: $this->registro_id,seccion: "em_empleado");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener link',
                data: $this->link_tg_empleado_asigna_provision_bd);
            print_r($error);
            exit;
        }

        $this->link_tg_empleado_asigna_percepcion_bd = $this->obj_link->link_con_id(accion: "asigna_percepcion_bd",link: $this->link,
            registro_id: $this->registro_id,seccion: "em_empleado");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener link',
                data: $this->link_tg_empleado_asigna_percepcion_bd);
            print_r($error);
            exit;
        }

        $this->link_dp_pais_alta = $this->obj_link->link_con_id(accion: "alta",link: $this->link,
            registro_id: $this->registro_id,seccion: "dp_pais");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener link',
                data: $this->link_dp_pais_alta);
            print_r($error);
            exit;
        }

        $this->link_dp_estado_alta = $this->obj_link->link_con_id(accion: "alta",link: $this->link,
            registro_id: $this->registro_id,seccion: "dp_estado");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener link',
                data: $this->link_dp_estado_alta);
            print_r($error);
            exit;
        }

        $this->link_dp_municipio_alta = $this->obj_link->link_con_id(accion: "alta",link: $this->link,
            registro_id: $this->registro_id,seccion: "dp_municipio");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener link',
                data: $this->link_dp_municipio_alta);
            print_r($error);
            exit;
        }

        $this->link_dp_cp_alta = $this->obj_link->link_con_id(accion: "alta",link: $this->link,
            registro_id: $this->registro_id,seccion: "dp_cp");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener link',
                data: $this->link_dp_cp_alta);
            print_r($error);
            exit;
        }

        $this->link_dp_colonia_alta = $this->obj_link->link_con_id(accion: "alta",link: $this->link,
            registro_id: $this->registro_id,seccion: "dp_colonia_postal");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener link',
                data: $this->link_dp_colonia_alta);
            print_r($error);
            exit;
        }

        $this->link_dp_calle_alta = $this->obj_link->link_con_id(accion: "alta",link: $this->link,
            registro_id: $this->registro_id,seccion: "dp_calle_pertenece");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener link',
                data: $this->link_dp_calle_alta);
            print_r($error);
            exit;
        }

        $sidebar = $this->init_sidebar();
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al inicializar sidebar', data: $sidebar);
            print_r($error);
            die('Error');
        }
    }

    public function alta(bool $header, bool $ws = false): array|string
    {
        $r_alta = $this->init_alta();
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al inicializar alta', data: $r_alta, header: $header, ws: $ws);
        }

        $keys_selects = $this->init_selects_inputs();
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al inicializar selects', data: $keys_selects, header: $header,
                ws: $ws);
        }

        $this->row_upd->fecha_inicio_rel_laboral = date('Y-m-d');
        $this->row_upd->fecha_antiguedad = date('Y-m-d');
        $this->row_upd->salario_diario = 0;
        $this->row_upd->salario_diario_integrado = 0;
        $this->row_upd->salario_total = 0;

        $inputs = $this->inputs(keys_selects: $keys_selects);
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs', data: $inputs, header: $header, ws: $ws);
        }

        return $r_alta;
    }

    protected function campos_view(): array
    {
        $keys = new stdClass();
        $keys->inputs = array('codigo', 'descripcion', 'nombre', 'ap', 'am',  'rfc', 'curp', 'nss', 'salario_diario',
            'salario_diario_integrado','com_sucursal','org_sucursal', 'salario_total','correo', 'monto');
        $keys->telefonos = array('telefono');
        $keys->fechas = array('fecha_inicio_rel_laboral', 'fecha_inicio', 'fecha_final', 'fecha_antiguedad');
        $keys->selects = array();

        $init_data = array();
        $init_data['dp_pais'] = "gamboamartin\\direccion_postal";
        $init_data['dp_estado'] = "gamboamartin\\direccion_postal";
        $init_data['dp_municipio'] = "gamboamartin\\direccion_postal";
        $init_data['dp_cp'] = "gamboamartin\\direccion_postal";
        $init_data['dp_colonia_postal'] = "gamboamartin\\direccion_postal";
        $init_data['dp_calle_pertenece'] = "gamboamartin\\direccion_postal";
        $init_data['cat_sat_regimen_fiscal'] = "gamboamartin\\cat_sat";
        $init_data['cat_sat_tipo_regimen_nom'] = "gamboamartin\\cat_sat";
        $init_data['cat_sat_tipo_jornada_nom'] = "gamboamartin\\cat_sat";
        $init_data['org_puesto'] = "gamboamartin\\organigrama";
        $init_data['em_centro_costo'] = "gamboamartin\\empleado";
        $init_data['em_empleado'] = "gamboamartin\\empleado";
        $init_data['em_registro_patronal'] = "gamboamartin\\empleado";
        $init_data['com_sucursal'] = "gamboamartin\\comercial";
        $init_data['org_sucursal'] = "gamboamartin\\organigrama";
        $init_data['nom_percepcion'] = "gamboamartin\\nomina";

        $campos_view = $this->campos_view_base(init_data: $init_data, keys: $keys);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al inicializar campo view', data: $campos_view);
        }

        return $campos_view;
    }

    public function init_sidebar(): stdClass|array
    {
        $menu_items = new stdClass();

        $menu_items->lista = $this->menu_item(menu_item_titulo: "Inicio", link: $this->link_lista);
        $menu_items->alta = $this->menu_item(menu_item_titulo: "Alta", link: $this->link_alta);
        $menu_items->modifica = $this->menu_item(menu_item_titulo: "Modifica", link: $this->link_modifica);
        $menu_items->importar = $this->menu_item(menu_item_titulo: "Importar Empleados", link: $this->link_em_empleado_sube_archivo);
        $menu_items->reportes = $this->menu_item(menu_item_titulo: "Reportes", link: $this->link_em_empleado_reportes);

        $menu_items->fiscales = $this->menu_item(menu_item_titulo: "Fiscales", link: $this->link_em_empleado_fiscales);
        $menu_items->imss = $this->menu_item(menu_item_titulo: "Imss", link: $this->link_em_empleado_imss);
        $menu_items->cuenta_bancaria = $this->menu_item(menu_item_titulo: "Cuenta Bancaria", link: $this->link_em_empleado_cuenta_bancaria);
        $menu_items->anticipo = $this->menu_item(menu_item_titulo: "Anticipo", link: $this->link_em_empleado_anticipo);
        $menu_items->asigna_cliente = $this->menu_item(menu_item_titulo: "Asigna Cliente", link: $this->link_em_empleado_asigna_sucursal);
        $menu_items->asigna_provision = $this->menu_item(menu_item_titulo: "Provisiones", link: $this->link_em_empleado_asigna_provision);
        $menu_items->asigna_percepcion = $this->menu_item(menu_item_titulo: "Percepciones", link: $this->link_em_empleado_asigna_percepcion);


        $menu_items->lista['menu_seccion_active'] = true;
        $menu_items->lista['menu_lateral_active'] = true;
        $menu_items->alta['menu_seccion_active'] = true;
        $menu_items->alta['menu_lateral_active'] = true;
        $menu_items->modifica['menu_seccion_active'] = true;
        $menu_items->modifica['menu_lateral_active'] = true;
        $menu_items->importar['menu_seccion_active'] = true;
        $menu_items->importar['menu_lateral_active'] = true;
        $menu_items->reportes['menu_seccion_active'] = true;
        $menu_items->reportes['menu_lateral_active'] = true;

        $menu_items->fiscales['menu_seccion_active'] = true;
        $menu_items->fiscales['menu_lateral_active'] = true;
        $menu_items->imss['menu_seccion_active'] = true;
        $menu_items->imss['menu_lateral_active'] = true;
        $menu_items->cuenta_bancaria['menu_seccion_active'] = true;
        $menu_items->cuenta_bancaria['menu_lateral_active'] = true;
        $menu_items->anticipo['menu_seccion_active'] = true;
        $menu_items->anticipo['menu_lateral_active'] = true;
        $menu_items->asigna_cliente['menu_seccion_active'] = true;
        $menu_items->asigna_cliente['menu_lateral_active'] = true;
        $menu_items->asigna_provision['menu_seccion_active'] = true;
        $menu_items->asigna_provision['menu_lateral_active'] = true;
        $menu_items->asigna_percepcion['menu_seccion_active'] = true;
        $menu_items->asigna_percepcion['menu_lateral_active'] = true;

        $this->sidebar['lista']['titulo'] = "Empleado";
        $this->sidebar['lista']['menu'] = array($menu_items->alta, $menu_items->importar, $menu_items->reportes);

        $menu_items->alta['menu_seccion_active'] = false;

        $this->sidebar['alta']['titulo'] = "Empleado";
        $this->sidebar['alta']['stepper_active'] = true;
        $this->sidebar['alta']['menu'] = array($menu_items->alta);

        $this->sidebar['modifica']['titulo'] = "Empleado";
        $this->sidebar['modifica']['stepper_active'] = true;
        $this->sidebar['modifica']['menu'] = array($menu_items->modifica, $menu_items->fiscales, $menu_items->imss,
            $menu_items->cuenta_bancaria, $menu_items->anticipo, $menu_items->asigna_cliente, $menu_items->asigna_provision,
            $menu_items->asigna_percepcion);

        $menu_items->importar['menu_seccion_active'] = false;

        $this->sidebar['sube_archivo']['titulo'] = "Empleado";
        $this->sidebar['sube_archivo']['stepper_active'] = true;
        $this->sidebar['sube_archivo']['menu'] = array($menu_items->importar);

        $this->sidebar['fiscales']['titulo'] = "Empleado";
        $this->sidebar['fiscales']['stepper_active'] = true;
        $this->sidebar['fiscales']['menu'] = array($menu_items->modifica, $menu_items->fiscales, $menu_items->imss,
            $menu_items->cuenta_bancaria, $menu_items->anticipo, $menu_items->asigna_cliente, $menu_items->asigna_provision,
            $menu_items->asigna_percepcion);

        $this->sidebar['imss']['titulo'] = "Empleado";
        $this->sidebar['imss']['stepper_active'] = true;
        $this->sidebar['imss']['menu'] = array($menu_items->modifica, $menu_items->fiscales, $menu_items->imss,
            $menu_items->cuenta_bancaria, $menu_items->anticipo, $menu_items->asigna_cliente, $menu_items->asigna_provision,
            $menu_items->asigna_percepcion);

        $this->sidebar['cuenta_bancaria']['titulo'] = "Empleado";
        $this->sidebar['cuenta_bancaria']['stepper_active'] = true;
        $this->sidebar['cuenta_bancaria']['menu'] = array($menu_items->modifica, $menu_items->fiscales, $menu_items->imss,
            $menu_items->cuenta_bancaria, $menu_items->anticipo, $menu_items->asigna_cliente, $menu_items->asigna_provision,
            $menu_items->asigna_percepcion);

        $this->sidebar['anticipo']['titulo'] = "Empleado";
        $this->sidebar['anticipo']['stepper_active'] = true;
        $this->sidebar['anticipo']['menu'] = array($menu_items->modifica, $menu_items->fiscales, $menu_items->imss,
            $menu_items->cuenta_bancaria, $menu_items->anticipo, $menu_items->asigna_cliente, $menu_items->asigna_provision,
            $menu_items->asigna_percepcion);

        $this->sidebar['asigna_sucursal']['titulo'] = "Empleado";
        $this->sidebar['asigna_sucursal']['stepper_active'] = true;
        $this->sidebar['asigna_sucursal']['menu'] = array($menu_items->modifica, $menu_items->fiscales, $menu_items->imss,
            $menu_items->cuenta_bancaria, $menu_items->anticipo, $menu_items->asigna_cliente, $menu_items->asigna_provision,
            $menu_items->asigna_percepcion);

        $this->sidebar['asigna_provision']['titulo'] = "Empleado";
        $this->sidebar['asigna_provision']['stepper_active'] = true;
        $this->sidebar['asigna_provision']['menu'] = array($menu_items->modifica, $menu_items->fiscales, $menu_items->imss,
            $menu_items->cuenta_bancaria, $menu_items->anticipo, $menu_items->asigna_cliente, $menu_items->asigna_provision,
            $menu_items->asigna_percepcion);

        $this->sidebar['asigna_percepcion']['titulo'] = "Empleado";
        $this->sidebar['asigna_percepcion']['stepper_active'] = true;
        $this->sidebar['asigna_percepcion']['menu'] = array($menu_items->modifica, $menu_items->fiscales, $menu_items->imss,
            $menu_items->cuenta_bancaria, $menu_items->anticipo, $menu_items->asigna_cliente, $menu_items->asigna_provision,
            $menu_items->asigna_percepcion);

        $this->sidebar['asigna_correo']['titulo'] = "Empleado";
        $this->sidebar['asigna_correo']['stepper_active'] = true;
        $this->sidebar['asigna_correo']['menu'] = array($menu_items->modifica, $menu_items->fiscales, $menu_items->imss,
            $menu_items->cuenta_bancaria, $menu_items->anticipo, $menu_items->asigna_cliente,
            $menu_items->asigna_percepcion);

        return $menu_items;
    }

    public function init_selects_inputs(): array
    {
        $keys_selects = parent::init_selects_inputs();
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }
        $keys_selects['em_centro_costo_id']->cols = 6;

        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "com_sucursal_id", label: "Cliente",
            cols: 12);
        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "em_empleado_id", label: "Empleado",
            cols: 12);
        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "nom_percepcion_id", label: "Percepcion",
            cols: 12);
        return $this->init_selects(keys_selects: $keys_selects, key: "org_sucursal_id", label: "Empresa",
            cols: 12);
    }

    private function init_selects(array $keys_selects, string $key, string $label, int $id_selected = -1, int $cols = 6,
                                  bool  $con_registros = true, array $filtro = array()): array
    {
        $keys_selects = $this->key_select(cols: $cols, con_registros: $con_registros, filtro: $filtro, key: $key,
            keys_selects: $keys_selects, id_selected: $id_selected, label: $label);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        return $keys_selects;
    }


    protected function key_selects_txt(array $keys_selects): array
    {
        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6, key: 'codigo',
            keys_selects: $keys_selects, place_holder: 'Código');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 4, key: 'nombre',
            keys_selects: $keys_selects, place_holder: 'Nombre');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 4, key: 'ap',
            keys_selects: $keys_selects, place_holder: 'Apellido Paterno');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 4, key: 'am',
            keys_selects: $keys_selects, place_holder: 'Apellido Materno');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6, key: 'fecha_antiguedad',
            keys_selects: $keys_selects, place_holder: 'Fecha Antigüedad');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6, key: 'monto',
            keys_selects: $keys_selects, place_holder: 'Monto');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }


        $keys_selects = parent::key_selects_txt(keys_selects: $keys_selects);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }



        return $keys_selects;
    }

    protected function inputs_children(stdClass $registro): array|stdClass
    {
        $this->inputs = parent::inputs_children(registro: $registro);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al obtener inputs children', data: $this->inputs);
        }

        if ($this->accion === "asigna_sucursal"){
            $r_template = $this->controlador_tg_empleado_sucursal->alta(header: false);
            if (errores::$error) {
                return $this->errores->error(mensaje: 'Error al obtener template', data: $r_template);
            }

            $keys_selects = $this->controlador_tg_empleado_sucursal->init_selects_inputs();
            if (errores::$error) {
                return $this->errores->error(mensaje: 'Error al inicializar selects', data: $keys_selects);
            }

            $keys_selects['em_empleado_id']->id_selected = $this->registro_id;
            $keys_selects['em_empleado_id']->filtro = array("em_empleado.id" => $this->registro_id);
            $keys_selects['em_empleado_id']->disabled = true;

            $inputs = $this->controlador_tg_empleado_sucursal->inputs(keys_selects: $keys_selects);
            if (errores::$error) {
                return $this->errores->error(mensaje: 'Error al obtener inputs', data: $inputs);
            }

            $this->inputs = $inputs;
        } else if ($this->accion === "asigna_provision"){
            $r_template = $this->alta(header: false);
            if (errores::$error) {
                return $this->errores->error(mensaje: 'Error al obtener template', data: $r_template);
            }

            $keys_selects = $this->init_selects_inputs();
            if (errores::$error) {
                return $this->errores->error(mensaje: 'Error al inicializar selects', data: $keys_selects);
            }

            $keys_selects['em_empleado_id']->id_selected = $this->registro_id;
            $keys_selects['em_empleado_id']->filtro = array("em_empleado.id" => $this->registro_id);
            $keys_selects['em_empleado_id']->disabled = true;

            $filtro['tg_empleado_sucursal.em_empleado_id'] = $this->registro_id;
            $clientes = (new tg_empleado_sucursal($this->link))->filtro_and(filtro: $filtro);
            if (errores::$error) {
                return $this->errores->error(mensaje: 'Error al obtener clientes', data: $clientes);
            }

            $registros_aplanados = array();

            foreach ($clientes->registros as $registro) {
                if (!in_array($registro['com_sucursal_id'], $registros_aplanados)){
                    $registros_aplanados[] = $registro['com_sucursal_id'];
                }
            }

            $keys_selects['com_sucursal_id']->in = array("llave" => 'com_sucursal.id', "values" => $registros_aplanados);
            $keys_selects['org_sucursal_id']->con_registros = false;

            $inputs = $this->inputs(keys_selects: $keys_selects);
            if (errores::$error) {
                return $this->errores->error(mensaje: 'Error al obtener inputs', data: $inputs);
            }

            $this->inputs = $inputs;
        } else if ($this->accion === "asigna_percepcion"){
            $r_template = $this->alta(header: false);
            if (errores::$error) {
                return $this->errores->error(mensaje: 'Error al obtener template', data: $r_template);
            }

            $keys_selects = $this->init_selects_inputs();
            if (errores::$error) {
                return $this->errores->error(mensaje: 'Error al inicializar selects', data: $keys_selects);
            }



            $inputs = $this->inputs(keys_selects: $keys_selects);
            if (errores::$error) {
                return $this->errores->error(mensaje: 'Error al obtener inputs', data: $inputs);
            }

            $this->inputs = $inputs;
        }

        return $this->inputs;
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

    public function fiscales(bool $header, bool $ws = false): array|stdClass
    {
        $r_modifica = $this->init_modifica();
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template', data: $r_modifica, header: $header, ws: $ws);
        }

        $keys_selects = $this->init_selects_inputs();
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al inicializar selects', data: $keys_selects, header: $header,
                ws: $ws);
        }

        $keys_selects['cat_sat_regimen_fiscal_id']->id_selected = $this->registro['cat_sat_regimen_fiscal_id'];
        $keys_selects['cat_sat_regimen_fiscal_id']->cols = 12;

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(), params_ajustados: array());
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar base', data: $base, header: $header, ws: $ws);
        }

        return $r_modifica;
    }

    public function imss(bool $header, bool $ws = false): array|stdClass
    {
        $r_modifica = $this->init_modifica();
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template', data: $r_modifica, header: $header, ws: $ws);
        }

        $keys_selects = $this->init_selects_inputs();
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al inicializar selects', data: $keys_selects, header: $header,
                ws: $ws);
        }

        $keys_selects['im_registro_patronal_id']->id_selected = $this->registro['im_registro_patronal_id'];
        $keys_selects['im_registro_patronal_id']->cols = 12;

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(), params_ajustados: array());
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar base', data: $base, header: $header, ws: $ws);
        }

        return $r_modifica;
    }

    public function asigna_correo(bool $header = true, bool $ws = false, array $not_actions = array()): array|string
    {
        $r_alta = $this->init_alta();
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al inicializar alta', data: $r_alta, header: $header, ws: $ws);
        }

        $keys_selects = $this->init_selects_inputs();
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al inicializar selects', data: $keys_selects, header: $header,
                ws: $ws);
        }

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(), params_ajustados: array());
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar base', data: $base, header: $header, ws: $ws);
        }

        return $r_alta;
    }

    public function asigna_sucursal(bool $header = true, bool $ws = false, array $not_actions = array()): array|string
    {
        $seccion = "tg_empleado_sucursal";

        $data_view = new stdClass();
        $data_view->names = array('Id', 'RFC Cliente', 'Cliente', 'RFC Empresa', 'Empresa', 'Acciones');
        $data_view->keys_data = array($seccion . "_id", 'com_cliente_rfc', 'com_sucursal_descripcion', 'org_empresa_rfc',
            'org_sucursal_descripcion');
        $data_view->key_actions = 'acciones';
        $data_view->namespace_model = 'tglobally\\tg_empleado\\models';
        $data_view->name_model_children = $seccion;

        $contenido_table = $this->contenido_children(data_view: $data_view, next_accion: __FUNCTION__,
            not_actions: $not_actions);
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al obtener tbody', data: $contenido_table, header: $header, ws: $ws);
        }

        return $contenido_table;
    }

    public function asigna_provision(bool $header = true, bool $ws = false, array $not_actions = array()): array|string
    {
        $seccion = "tg_conf_provisiones_empleado";

        $data_view = new stdClass();
        $data_view->names = array('Id', 'Cliente','Empresa','Provisión');
        $data_view->keys_data = array($seccion . "_id", "com_sucursal_descripcion", "org_sucursal_descripcion",
            "tg_tipo_provision_descripcion");
        $data_view->key_actions = 'acciones';
        $data_view->namespace_model = 'tglobally\\tg_empleado\\models';
        $data_view->name_model_children = $seccion;

        $contenido_table = $this->contenido_children(data_view: $data_view, next_accion: __FUNCTION__,
            not_actions: $not_actions);
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al obtener tbody', data: $contenido_table, header: $header, ws: $ws);
        }

        return $contenido_table;
    }

    public function asigna_percepcion(bool $header = true, bool $ws = false, array $not_actions = array()): array|string
    {
        $seccion = "tg_conf_percepcion_empleado";

        $data_view = new stdClass();
        $data_view->names = array('Id', 'Cliente', 'Percepcion', 'Monto');
        $data_view->keys_data = array($seccion . "_id", "com_sucursal_descripcion", "nom_percepcion_descripcion",
            $seccion.'_monto');
        $data_view->key_actions = 'acciones';
        $data_view->namespace_model = 'tglobally\\tg_empleado\\models';
        $data_view->name_model_children = $seccion;

        $contenido_table = $this->contenido_children(data_view: $data_view, next_accion: __FUNCTION__,
            not_actions: $not_actions);
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al obtener tbody', data: $contenido_table, header: $header, ws: $ws);
        }

        return $contenido_table;
    }

    public function asigna_provision_bd(bool $header = true, bool $ws = false, array $not_actions = array()): array|string
    {
        $this->link->beginTransaction();

        $siguiente_view = $this->inicializa_transaccion();
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(
                mensaje: 'Error al inicializar', data: $siguiente_view, header: $header, ws: $ws);
        }

        $provisiones = $this->determinar_provisiones(registros: $_POST);

        $filtro['tg_conf_provision.com_sucursal_id'] = $_POST['com_sucursal_id'];
        $filtro['tg_conf_provision.org_sucursal_id'] = $_POST['org_sucursal_id'];
        $filtro['tg_conf_provision.estado'] = "activo";
        $configuracion = (new tg_conf_provision($this->link))->filtro_and(filtro: $filtro);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al obtener configuracion', data: $configuracion);
        }

        $configuracion_id = -1;

        if ($configuracion->n_registros > 0){
            $filtro['tg_conf_provisiones_empleado.tg_conf_provision_id'] = $configuracion->registros[0]['tg_conf_provision_id'];
            $filtro['tg_conf_provisiones_empleado.em_empleado_id'] = $this->registro_id;
            $borrados = (new tg_conf_provisiones_empleado($this->link))->elimina_con_filtro_and(filtro: $filtro);
            if (errores::$error) {
                $this->link->rollBack();
                return $this->errores->error(mensaje: 'Error al eliminar provisiones', data: $borrados);
            }

            $configuracion_id = $configuracion->registros[0]['tg_conf_provision_id'];
        } else {
            $alta['com_sucursal_id'] = $_POST['com_sucursal_id'];
            $alta['org_sucursal_id'] = $_POST['org_sucursal_id'];
            $alta['estado'] = "activo";
            $alta['descripcion'] = "CONF.";
            $alta['codigo'] = $this->modelo->get_codigo_aleatorio();
            $alta['codigo_bis'] = $alta['codigo'];

            $alta_bd = (new tg_conf_provision($this->link))->alta_registro(registro: $alta);
            if (errores::$error) {
                $this->link->rollBack();
                return $this->errores->error(mensaje: 'Error al insertar configuracion', data: $alta_bd);
            }

            $configuracion_id = $alta_bd->registro_id;
        }

        $tipo_calculo = "inactive";

        if (isset($_POST['tipo_calculo']) && $_POST['tipo_calculo'] === "active"){
            $tipo_calculo = "active";
        }

        foreach ($provisiones as $provision){
            $filtro = array();
            $filtro['tg_tipo_provision.descripcion'] = $provision;
            $id_provision = (new tg_tipo_provision($this->link))->filtro_and(columnas: array('tg_tipo_provision_id'),
                filtro: $filtro);
            if (errores::$error) {
                return $this->errores->error(mensaje: 'Error al obtener provision', data: $id_provision);
            }

            $alta['tg_conf_provision_id'] = $configuracion_id;
            $alta['em_empleado_id'] = $this->registro_id;
            $alta['tg_tipo_provision_id'] = $id_provision->registros[0]['tg_tipo_provision_id'];
            $alta['descripcion'] = $provision;
            $alta['codigo'] = $this->modelo->get_codigo_aleatorio();
            $alta['codigo_bis'] = $alta['codigo'];
            $alta['tipo_calculo'] = $tipo_calculo;
            $alta_bd = (new tg_conf_provisiones_empleado($this->link))->alta_registro(registro: $alta);
            if (errores::$error) {
                $this->link->rollBack();
                return $this->errores->error(mensaje: 'Error al insertar provision', data: $alta_bd);
            }
        }


        $this->link->commit();
        $link = "./index.php?seccion=em_empleado&accion=asigna_provision&registro_id=" . $this->registro_id;
        $link .= "&session_id=$this->session_id";
        header('Location:' . $link);
        exit();
    }

    public function determinar_provisiones(array $registros): array
    {
        unset($registros['com_sucursal_id']);
        unset($registros['org_sucursal_id']);
        unset($registros['tipo_calculo']);
        return $registros;
    }

    public function asigna_sucursal_bd(bool $header = true, bool $ws = false, array $not_actions = array()): array|string
    {
        $this->link->beginTransaction();

        $siguiente_view = $this->inicializa_transaccion();
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(
                mensaje: 'Error al inicializar', data: $siguiente_view, header: $header, ws: $ws);
        }

        $registro['em_empleado_id'] = $this->registro_id;
        $registro['com_sucursal_id'] = $_POST['com_sucursal_id'];
        $registro['org_sucursal_id'] = $_POST['org_sucursal_id'];

        $alta = (new tg_empleado_sucursal($this->link))->alta_registro(registro: $registro);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al dar de alta cuenta bancaria', data: $alta,
                header: $header, ws: $ws);
        }

        $this->link->commit();

        if ($header) {
            $this->retorno_base(registro_id:$this->registro_id, result: $alta,
                siguiente_view: "asigna_sucursal", ws:  $ws);
        }
        if ($ws) {
            header('Content-Type: application/json');
            try {
                echo json_encode($alta, JSON_THROW_ON_ERROR);
            }
            catch (Throwable $e){
                $error = (new errores())->error(mensaje:'Error', data: $e);
                print_r($error);
            }
            exit;
        }
        $alta->siguiente_view = "asigna_sucursal";

        return $alta;
    }

    public function ajusta_puestos(bool $header, bool $ws = false)
    {
        $em_empleado = (new em_empleado($this->link))->registro(registro_id: $this->registro_id);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener manifiesto', data: $em_empleado);
            if (!$header) {
                return $error;
            }
            print_r($error);
            die('Error');
        }

        $doc_documento_modelo = new doc_documento($this->link);
        $doc_documento_modelo->registro['descripcion'] = $em_empleado['em_empleado_descripcion'];
        $doc_documento_modelo->registro['descripcion_select'] = $em_empleado['em_empleado_descripcion'];
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

        $empleados_excel = $this->obten_empleados_excel(ruta_absoluta: $doc_documento->registro['doc_documento_ruta_absoluta']);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error obtener empleados', data: $empleados_excel);
            if (!$header) {
                return $error;
            }
            print_r($error);
            die('Error');
        }

        $filtro['em_registro_patronal.id'] = $em_empleado['em_registro_patronal_id'];
        $em_registro_patronal = (new em_registro_patronal($this->link))->filtro_and(filtro: $filtro);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error obtener registro patronal', data: $em_registro_patronal);
        }

        $em_registro_patronal_id = $em_registro_patronal->registros[0]['em_registro_patronal_id'];
        $empleados = array();

        foreach ($empleados_excel as $empleado){

            $registro = array();
            $keys = array('codigo','nombre','ap','am','telefono','curp','rfc','nss','fecha_inicio_rel_laboral',
                'salario_diario','salario_diario');
            foreach ($keys as $key){
                if(isset($empleado->$key)){
                    $registro[$key] = $empleado->$key;
                }
            }

            $em_empleado = new \gamboamartin\empleado\models\em_empleado($this->link);
            $em_empleado->registro = $registro;
            $r_alta = $em_empleado->alta_bd();
            if (errores::$error) {
                $error = $this->errores->error(mensaje: 'Error al dar de alta registro', data: $r_alta);
                if (!$header) {
                    return $error;
                }
                print_r($error);
                die('Error');
            }
        }

        $link = "./index.php?seccion=em_empleado&accion=lista&registro_id=" . $this->registro_id;
        $link .= "&session_id=$this->session_id";
        header('Location:' . $link);
        exit;
    }

    public function obten_empleados_excel(string $ruta_absoluta)
    {
        $documento = IOFactory::load($ruta_absoluta);
        $totalDeHojas = $documento->getSheetCount();

        $empleados = array();
        for ($indiceHoja = 0; $indiceHoja < $totalDeHojas; $indiceHoja++) {
            $hojaActual = $documento->getSheet($indiceHoja);
            $registros = array();
            foreach ($hojaActual->getRowIterator() as $fila) {
                foreach ($fila->getCellIterator() as $celda) {
                    $fila = $celda->getRow();
                    $valorRaw = $celda->getValue();
                    $columna = $celda->getColumn();

                    if ($fila >= 7) {
                        if ($columna === "A" && is_numeric($valorRaw)) {
                            $reg = new stdClass();
                            $reg->fila = $fila;
                            $registros[] = $reg;
                        }
                    }
                }
            }

            foreach ($registros as $registro) {
                $reg = new stdClass();
                $reg->codigo = $hojaActual->getCell('A' . $registro->fila)->getValue();
                $reg->nombre = $hojaActual->getCell('B' . $registro->fila)->getValue();
                $reg->ap = $hojaActual->getCell('C' . $registro->fila)->getValue();
                $reg->am = $hojaActual->getCell('D' . $registro->fila)->getValue();
                $reg->faltas = 0;
                $reg->prima_dominical = 0;
                $reg->dias_festivos_laborados = 0;
                $reg->incapacidades = 0;
                $reg->vacaciones = 0;
                $reg->dias_descanso_laborado = 0;
                $reg->compensacion = 0;

                $reg->prima_vacacional = 0;
                $reg->despensa = 0;
                $reg->actividades_culturales = 0;
                $reg->seguro_vida = 0;
                $reg->caja_ahorro = 0;
                $reg->anticipo_nomina = 0;
                $reg->pension_alimenticia = 0;
                $reg->descuentos = 0;
                $reg->horas_extras_dobles = 0;
                $reg->horas_extras_triples = 0;
                $reg->gratificacion_especial = 0;
                $reg->premio_puntualidad = 0;
                $reg->premio_asistencia = 0;
                $reg->ayuda_transporte = 0;
                $reg->productividad = 0;
                $reg->gratificacion = 0;
                $reg->monto_neto = 0;
                $reg->monto_sueldo = 0;

                $empleados[] = $reg;
            }
        }

        return $empleados;
    }

    public function lee_archivo(bool $header, bool $ws = false)
    {
        $doc_documento_modelo = new doc_documento($this->link);
        $doc_documento_modelo->registro['descripcion'] = "Alta empleados ". rand();
        $doc_documento_modelo->registro['descripcion_select'] = rand();
        $doc_documento_modelo->registro['doc_tipo_documento_id'] = 1;
        $doc_documento = $doc_documento_modelo->alta_bd(file: $_FILES['archivo']);
        if (errores::$error) {
            $error =  $this->errores->error(mensaje: 'Error al dar de alta el documento', data: $doc_documento);
            if(!$header){
                return $error;
            }
            print_r($error);
            die('Error');
        }

        $columnas = array("CODIGO", "NOMBRE", "APELLIDO PATERNO", "APELLIDO MATERNO", "TELEFONO", "CURP", "RFC",
            "NSS", "FECHA DE INGRESO", "FECHA ANTIGUEDAD", "SALARIO DIARIO", "FACTOR DE INTEGRACION", "SALARIO DIARIO INTEGRADO",
            "SALARIO TOTAL","BANCO", "NUMERO DE CUENTA", "CLABE", "NOMINA", "CLIENTE");
        $fechas = array("FECHA DE INGRESO", "FECHA ANTIGUEDAD");

        $empleados_excel = Importador::getInstance()
            ->leer_registros(ruta_absoluta: $doc_documento->registro['doc_documento_ruta_absoluta'], columnas: $columnas,
                fechas: $fechas);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al leer archivo de anticipos', data: $empleados_excel);
            if (!$header) {
                return $error;
            }
            print_r($error);
            die('Error');
        }

        $this->link->beginTransaction();

        foreach ($empleados_excel as $empleado){
            $empleado = (array)$empleado;

            $registros_empleado['codigo'] = $empleado['CODIGO'];
            $registros_empleado['nombre'] = $empleado['NOMBRE'];
            $registros_empleado['ap'] = $empleado['APELLIDO PATERNO'];
            $registros_empleado['am'] = $empleado['APELLIDO MATERNO'];
            $registros_empleado['telefono'] = $empleado['TELEFONO'];
            $registros_empleado['curp'] = $empleado['CURP'];
            $registros_empleado['rfc'] = $empleado['RFC'];
            $registros_empleado['nss'] = $empleado['NSS'];
            $registros_empleado['fecha_inicio_rel_laboral'] = $empleado['FECHA DE INGRESO'];
            $registros_empleado['fecha_antiguedad'] = $empleado['FECHA ANTIGUEDAD'];
            $registros_empleado['salario_diario'] = $empleado['SALARIO DIARIO'];
            $registros_empleado['salario_total'] = $empleado['SALARIO TOTAL'];

            $filtro_bn_sucursal['bn_sucursal.descripcion'] = strtoupper($empleado['BANCO']);
            $bn_sucursal = (new bn_sucursal($this->link))->filtro_and(columnas: array('bn_sucursal_id'),
                filtro: $filtro_bn_sucursal, limit: 1);
            if (errores::$error) {
                $error = $this->errores->error(mensaje: 'Error al obtener banco', data: $bn_sucursal);
                if (!$header) {
                    return $error;
                }
                print_r($error);
                die('Error');
            }

            if ($bn_sucursal->n_registros <= 0){
                $error = $this->errores->error(mensaje: 'Error no existe el banco', data: $empleado['BANCO']);
                if (!$header) {
                    return $error;
                }
                print_r($error);
                die('Error');
            }

            $registros_empleado['com_sucursal_id'] = $empleado['CLIENTE'];
            $registros_empleado['bn_sucursal_id'] = $bn_sucursal->registros[0]['bn_sucursal_id'];
            $registros_empleado['num_cuenta'] = $empleado['NUMERO DE CUENTA'];
            $registros_empleado['clabe'] = $empleado['CLABE'];

            $alta_empleado = (new em_empleado($this->link))->alta_registro(registro: $registros_empleado);
            if (errores::$error) {
                $this->link->rollBack();
                $error = $this->errores->error(mensaje: 'Error al dar de alta empleado', data: $alta_empleado);
                if (!$header) {
                    return $error;
                }
                print_r($error);
                die('Error');
            }
        }

        $this->link->commit();

        header('Location:' . $this->link_lista);
        exit;
    }



    /**
     * Limpia boton de siguiente accion
     * @return array
     * @version 0.65.8
     */
    private function clean_post(): array
    {
        if (isset($_POST['btn_action_next'])) {
            unset($_POST['btn_action_next']);
        }
        return $_POST;
    }

    public function cuenta_bancaria_alta_bd(bool $header, bool $ws = false)
    {
        $this->link->beginTransaction();

        $siguiente_view = $this->inicializa_transaccion();
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(
                mensaje: 'Error al inicializar', data: $siguiente_view, header: $header, ws: $ws);
        }

        $_POST['em_empleado_id'] = $this->registro_id;

        $alta = (new em_cuenta_bancaria($this->link))->alta_registro(registro: $_POST);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al dar de alta cuenta bancaria', data: $alta,
                header: $header, ws: $ws);
        }

        $this->link->commit();

        if ($header) {
            $this->retorno_base(registro_id:$this->registro_id, result: $alta,
                siguiente_view: "cuenta_bancaria", ws:  $ws);
        }
        if ($ws) {
            header('Content-Type: application/json');
            try {
                echo json_encode($alta, JSON_THROW_ON_ERROR);
            }
            catch (Throwable $e){
                $error = (new errores())->error(mensaje:'Error', data: $e);
                print_r($error);
            }
            exit;
        }
        $alta->siguiente_view = "cuenta_bancaria";

        return $alta;

    }

    public function cuenta_bancaria_modifica(bool $header, bool $ws = false): array|stdClass
    {
        $this->controlador_em_cuenta_bancaria->registro_id = $this->em_cuenta_bancaria_id;

        $modifica = $this->controlador_em_cuenta_bancaria->modifica(header: false);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar template',data:  $modifica, header: $header,ws:$ws);
        }

        $this->inputs = $this->controlador_em_cuenta_bancaria->genera_inputs(
            keys_selects:  $this->controlador_em_cuenta_bancaria->keys_selects);
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al generar inputs',data:  $this->inputs);
            print_r($error);
            die('Error');
        }

        return $this->inputs;
    }

    public function cuenta_bancaria_modifica_bd(bool $header, bool $ws = false): array|stdClass
    {
        $this->link->beginTransaction();

        $siguiente_view = (new actions())->init_alta_bd();
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener siguiente view', data: $siguiente_view,
                header: $header, ws: $ws);
        }

        if (isset($_POST['btn_action_next'])) {
            unset($_POST['btn_action_next']);
        }

        $registros = $_POST;

        $r_modifica = (new em_cuenta_bancaria($this->link))->modifica_bd(registro: $registros,
            id: $this->em_cuenta_bancaria_id);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al modificar deduccion', data: $r_modifica, header: $header, ws: $ws);
        }

        $this->link->commit();

        if ($header) {
            $this->retorno_base(registro_id:$this->registro_id, result: $r_modifica,
                siguiente_view: "cuenta_bancaria", ws:  $ws);
        }
        if ($ws) {
            header('Content-Type: application/json');
            echo json_encode($r_modifica, JSON_THROW_ON_ERROR);
            exit;
        }
        $r_modifica->siguiente_view = "cuenta_bancaria";

        return $r_modifica;
    }

    public function cuenta_bancaria_elimina_bd(bool $header, bool $ws = false): array|stdClass
    {
        $this->link->beginTransaction();

        $siguiente_view = (new actions())->init_alta_bd();
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener siguiente view', data: $siguiente_view,
                header: $header, ws: $ws);
        }

        if (isset($_POST['btn_action_next'])) {
            unset($_POST['btn_action_next']);
        }

        $r_elimina = (new em_cuenta_bancaria($this->link))->elimina_bd(id: $this->em_cuenta_bancaria_id);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al eliminar otro pago', data: $r_elimina, header: $header,
                ws: $ws);
        }

        $this->link->commit();

        if ($header) {
            $this->retorno_base(registro_id:$this->registro_id, result: $r_elimina,
                siguiente_view: "cuenta_bancaria", ws:  $ws);
        }
        if ($ws) {
            header('Content-Type: application/json');
            echo json_encode($r_elimina, JSON_THROW_ON_ERROR);
            exit;
        }
        $r_elimina->siguiente_view = "cuenta_bancaria";

        return $r_elimina;
    }

    private function data_empleado_sucursal_btn(array $empleado_sucursal): array
    {
        $params['tg_empleado_sucursal'] = $empleado_sucursal['tg_empleado_sucursal_id'];

        $btn_elimina = $this->html_base->button_href(accion: 'empleado_sucursal_elimina_bd', etiqueta: 'Elimina',
            registro_id: $this->registro_id, seccion: 'em_empleado', style: 'danger',params: $params);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al generar btn', data: $btn_elimina);
        }
        $empleado_sucursal['link_elimina'] = $btn_elimina;

        $btn_modifica = $this->html_base->button_href(accion: 'empleado_sucursal_modifica', etiqueta: 'Modifica',
            registro_id: $this->registro_id, seccion: 'em_empleado', style: 'warning',params: $params);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al generar btn', data: $btn_modifica);
        }
        $empleado_sucursal['link_modifica'] = $btn_modifica;

        return $empleado_sucursal;
    }

    public function empleado_sucursal_alta_bd(bool $header, bool $ws = false)
    {
        $this->link->beginTransaction();


        $siguiente_view = $this->inicializa_transaccion();
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(
                mensaje: 'Error al inicializar', data: $siguiente_view, header: $header, ws: $ws);
        }

        $_POST['em_empleado_id'] = $this->registro_id;

        $codigo = rand(1,10000).$_POST['em_empleado_id'].' '.$_POST['com_sucursal_id'];

        if (!isset($_POST['codigo'])) {
            $_POST['codigo'] = $codigo;
        }

        if (!isset($_POST['codigo_bis'])) {
            $_POST['codigo_bis'] = $codigo;
        }

        if (!isset($_POST['descripcion'])) {
            $_POST['descripcion'] = $codigo;
        }

        $alta = (new tg_empleado_sucursal($this->link))->alta_registro(registro: $_POST);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al dar de alta empleado sucursal bancaria', data: $alta,
                header: $header, ws: $ws);
        }

        $this->link->commit();

        if ($header) {
            $this->retorno_base(registro_id:$this->registro_id, result: $alta,
                siguiente_view: "asigna_sucursal", ws:  $ws);
        }
        if ($ws) {
            header('Content-Type: application/json');
            echo json_encode($alta, JSON_THROW_ON_ERROR);
            exit;
        }
        $alta->siguiente_view = "asigna_sucursal";

        return $alta;

    }

    private function inicializa_transaccion(): array|string
    {
        $siguiente_view = (new actions())->init_alta_bd();
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al obtener siguiente view', data: $siguiente_view);
        }

        $limpia = $this->clean_post();
        if (errores::$error) {

            return $this->errores->error(mensaje: 'Error al limpiar post', data: $limpia);
        }

        return $siguiente_view;
    }

    public function reportes(bool $header, bool $ws = false): array|stdClass
    {
        $this->modelo->campos_view['tg_cte_alianza_id'] = array('type' => 'selects', 'model' => new com_sucursal($this->link));

        $this->asignar_propiedad(identificador:'tg_cte_alianza_id', propiedades: ["label" => "Sucursal","required"=>false]);

        $reporte = parent::reportes($header, $ws);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al obtener view reprote', data: $reporte);
        }

        return  $reporte;
    }


}
