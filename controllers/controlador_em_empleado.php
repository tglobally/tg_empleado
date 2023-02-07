<?php
namespace tglobally\tg_empleado\controllers;

use gamboamartin\comercial\models\com_sucursal;
use gamboamartin\empleado\models\em_cuenta_bancaria;
use gamboamartin\errores\errores;
use gamboamartin\system\actions;
use tglobally\tg_empleado\models\em_empleado;
use tglobally\tg_empleado\models\tg_empleado_sucursal;
use PDO;
use stdClass;
use tglobally\template_tg\html;
use Throwable;

class controlador_em_empleado extends \gamboamartin\empleado\controllers\controlador_em_empleado {
    public controlador_tg_empleado_sucursal $controlador_tg_empleado_sucursal;
    public stdClass $tg_empleado_sucursal;
    public string $link_em_empleado_fiscales = '';
    public string $link_em_empleado_imss = '';
    public string $link_em_empleado_cuenta_bancaria = '';
    public string $link_em_empleado_anticipo = '';
    public string $link_em_empleado_asigna_sucursal = '';
    public string $link_tg_empleado_sucursal_alta_bd = '';

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

        $this->link_tg_empleado_sucursal_alta_bd = $this->obj_link->link_alta_bd(link: $this->link,
            seccion: 'tg_empleado_sucursal');
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener link',
                data: $this->link_tg_empleado_sucursal_alta_bd);
            print_r($error);
            exit;
        }

        $this->sidebar['lista']['titulo'] = "Empleados";
        $this->sidebar['lista']['menu'] = array(
            $this->menu_item(menu_item_titulo: "Alta", link: $this->link_alta,menu_seccion_active: true,
                menu_lateral_active: true),
            $this->menu_item(menu_item_titulo: "Sube Empleados", link: $this->link_em_empleado_sube_archivo,menu_seccion_active: true,
                menu_lateral_active: true),
            $this->menu_item(menu_item_titulo: "Reportes", link: $this->link_em_empleado_reportes,menu_seccion_active: true,
                menu_lateral_active: true));

        $this->sidebar['sube_archivo']['titulo'] = "Empleados";
        $this->sidebar['sube_archivo']['menu'] = array(
            $this->menu_item(menu_item_titulo: "Alta", link: $this->link_alta,menu_seccion_active: true,
                menu_lateral_active: true),
            $this->menu_item(menu_item_titulo: "Sube Empleados", link: $this->link_em_empleado_sube_archivo,menu_seccion_active: true,
                menu_lateral_active: true),
            $this->menu_item(menu_item_titulo: "Reportes", link: $this->link_em_empleado_reportes,menu_seccion_active: true,
                menu_lateral_active: true));

        $this->sidebar['reportes']['titulo'] = "Empleados";
        $this->sidebar['reportes']['menu'] = array(
            $this->menu_item(menu_item_titulo: "Alta", link: $this->link_alta,menu_seccion_active: true,
                menu_lateral_active: true),
            $this->menu_item(menu_item_titulo: "Sube Empleados", link: $this->link_em_empleado_sube_archivo,menu_seccion_active: true,
                menu_lateral_active: true),
            $this->menu_item(menu_item_titulo: "Reportes", link: $this->link_em_empleado_reportes,menu_seccion_active: true,
                menu_lateral_active: true));

        $this->sidebar['alta']['titulo'] = "Empleado";
        $this->sidebar['alta']['stepper_active'] = true;
        $this->sidebar['alta']['menu'] = array(
            $this->menu_item(menu_item_titulo: "Alta", link: $this->link_alta,menu_lateral_active: true));

        $this->sidebar['modifica']['titulo'] = "Empleado";
        $this->sidebar['modifica']['stepper_active'] = true;
        $this->sidebar['modifica']['menu'] = array(
            $this->menu_item(menu_item_titulo: "Modifica", link: $this->link_modifica),
            $this->menu_item(menu_item_titulo: "Fiscales", link: $this->link_em_empleado_fiscales,menu_seccion_active: true,
                menu_lateral_active: true),
            $this->menu_item(menu_item_titulo: "Imss", link: $this->link_em_empleado_imss,menu_seccion_active: true,
                menu_lateral_active: true),
            $this->menu_item(menu_item_titulo: "Cuenta Bancaria", link: $this->link_em_empleado_cuenta_bancaria,
                menu_seccion_active: true, menu_lateral_active: true),
            $this->menu_item(menu_item_titulo: "Anticipo", link: $this->link_em_empleado_anticipo,menu_seccion_active: true,
                menu_lateral_active: true),
            $this->menu_item(menu_item_titulo: "Asigna Sucursal", link: $this->link_em_empleado_asigna_sucursal,
                menu_seccion_active: true, menu_lateral_active: true));

        $this->sidebar['fiscales']['titulo'] = "Empleado";
        $this->sidebar['fiscales']['stepper_active'] = true;
        $this->sidebar['fiscales']['menu'] = array(
            $this->menu_item(menu_item_titulo: "Modifica", link: $this->link_modifica,menu_seccion_active: true,
                menu_lateral_active: true),
            $this->menu_item(menu_item_titulo: "Fiscales", link: $this->link_em_empleado_fiscales),
            $this->menu_item(menu_item_titulo: "Imss", link: $this->link_em_empleado_imss,menu_seccion_active: true,
                menu_lateral_active: true),
            $this->menu_item(menu_item_titulo: "Cuenta Bancaria", link: $this->link_em_empleado_cuenta_bancaria,menu_seccion_active: true,
                menu_lateral_active: true),
            $this->menu_item(menu_item_titulo: "Anticipo", link: $this->link_em_empleado_anticipo,menu_seccion_active: true,
                menu_lateral_active: true),
            $this->menu_item(menu_item_titulo: "Asigna Sucursal", link: $this->link_em_empleado_asigna_sucursal,
                menu_seccion_active: true, menu_lateral_active: true));

        $this->sidebar['imss']['titulo'] = "Empleado";
        $this->sidebar['imss']['stepper_active'] = true;
        $this->sidebar['imss']['menu'] = array(
            $this->menu_item(menu_item_titulo: "Modifica", link: $this->link_modifica,menu_seccion_active: true,
                menu_lateral_active: true),
            $this->menu_item(menu_item_titulo: "Fiscales", link: $this->link_em_empleado_fiscales,
                menu_seccion_active: true, menu_lateral_active: true),
            $this->menu_item(menu_item_titulo: "Imss", link: $this->link_em_empleado_imss),
            $this->menu_item(menu_item_titulo: "Cuenta Bancaria", link: $this->link_em_empleado_cuenta_bancaria,
                menu_seccion_active: true, menu_lateral_active: true),
            $this->menu_item(menu_item_titulo: "Anticipo", link: $this->link_em_empleado_anticipo,menu_seccion_active: true,
                menu_lateral_active: true),
            $this->menu_item(menu_item_titulo: "Asigna Sucursal", link: $this->link_em_empleado_asigna_sucursal,
                menu_seccion_active: true, menu_lateral_active: true));

        $this->sidebar['cuenta_bancaria']['titulo'] = "Empleado";
        $this->sidebar['cuenta_bancaria']['stepper_active'] = true;
        $this->sidebar['cuenta_bancaria']['menu'] = array(
            $this->menu_item(menu_item_titulo: "Modifica", link: $this->link_modifica,menu_seccion_active: true,
                menu_lateral_active: true),
            $this->menu_item(menu_item_titulo: "Fiscales", link: $this->link_em_empleado_fiscales,
                menu_seccion_active: true, menu_lateral_active: true),
            $this->menu_item(menu_item_titulo: "Imss", link: $this->link_em_empleado_imss,menu_seccion_active: true,
                menu_lateral_active: true),
            $this->menu_item(menu_item_titulo: "Cuenta Bancaria", link: $this->link_em_empleado_cuenta_bancaria),
            $this->menu_item(menu_item_titulo: "Anticipo", link: $this->link_em_empleado_anticipo,menu_seccion_active: true,
                menu_lateral_active: true),
            $this->menu_item(menu_item_titulo: "Asigna Sucursal", link: $this->link_em_empleado_asigna_sucursal,
                menu_seccion_active: true, menu_lateral_active: true));

        $this->sidebar['anticipo']['titulo'] = "Empleado";
        $this->sidebar['anticipo']['stepper_active'] = true;
        $this->sidebar['anticipo']['menu'] = array(
            $this->menu_item(menu_item_titulo: "Modifica", link: $this->link_modifica,menu_seccion_active: true,
                menu_lateral_active: true),
            $this->menu_item(menu_item_titulo: "Fiscales", link: $this->link_em_empleado_fiscales,
                menu_seccion_active: true, menu_lateral_active: true),
            $this->menu_item(menu_item_titulo: "Imss", link: $this->link_em_empleado_imss,menu_seccion_active: true,
                menu_lateral_active: true),
            $this->menu_item(menu_item_titulo: "Cuenta Bancaria", link: $this->link_em_empleado_cuenta_bancaria,
                menu_seccion_active: true, menu_lateral_active: true),
            $this->menu_item(menu_item_titulo: "Anticipo", link: $this->link_em_empleado_anticipo),
            $this->menu_item(menu_item_titulo: "Asigna Sucursal", link: $this->link_em_empleado_asigna_sucursal,
                menu_seccion_active: true, menu_lateral_active: true));

        $this->sidebar['asigna_sucursal']['titulo'] = "Empleado";
        $this->sidebar['asigna_sucursal']['stepper_active'] = true;
        $this->sidebar['asigna_sucursal']['menu'] = array(
            $this->menu_item(menu_item_titulo: "Modifica", link: $this->link_modifica,menu_seccion_active: true,
                menu_lateral_active: true),
            $this->menu_item(menu_item_titulo: "Fiscales", link: $this->link_em_empleado_fiscales,
                menu_seccion_active: true, menu_lateral_active: true),
            $this->menu_item(menu_item_titulo: "Imss", link: $this->link_em_empleado_imss,menu_seccion_active: true,
                menu_lateral_active: true),
            $this->menu_item(menu_item_titulo: "Cuenta Bancaria", link: $this->link_em_empleado_cuenta_bancaria,
                menu_seccion_active: true, menu_lateral_active: true),
            $this->menu_item(menu_item_titulo: "Anticipo", link: $this->link_em_empleado_anticipo,
                menu_seccion_active: true, menu_lateral_active: true),
            $this->menu_item(menu_item_titulo: "Asigna Sucursal", link: $this->link_em_empleado_asigna_sucursal));
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

    public function asigna_sucursal(bool $header = true, bool $ws = false, array $not_actions = array()): array|string
    {
        $seccion = "tg_empleado_sucursal";

        $data_view = new stdClass();
        $data_view->names = array('Id', 'Código', 'RFC Cliente', 'Razón Social Client', 'Sucursal Cliente', 'Acciones');
        $data_view->keys_data = array($seccion . "_id", $seccion . '_codigo', 'com_cliente_rfc', 'com_cliente_razon_social',
            'com_sucursal_descripcion');
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
