<?php
namespace tglobally\tg_empleado\controllers;


use gamboamartin\comercial\models\com_sucursal;
use gamboamartin\empleado\models\em_cuenta_bancaria;
use gamboamartin\errores\errores;
use gamboamartin\nomina\controllers\controlador_nom_conf_empleado;
use gamboamartin\nomina\models\nom_conf_empleado;
use gamboamartin\system\actions;
use models\em_empleado;
use models\im_conf_pres_empresa;
use models\tg_empleado_sucursal;
use PDO;
use stdClass;
use tglobally\template_tg\html;
use Throwable;

class controlador_em_empleado extends \gamboamartin\empleado\controllers\controlador_em_empleado {
    public controlador_tg_empleado_sucursal $controlador_tg_empleado_sucursal;
    public controlador_nom_conf_empleado $controlador_nom_conf_empleado;
    public stdClass $tg_empleado_sucursal;
    public stdClass $nom_conf_empleado;

    public function __construct(PDO $link, stdClass $paths_conf = new stdClass())
    {


        $html_base = new html();
        parent::__construct(link: $link, html: $html_base, paths_conf: $paths_conf);

        $modelo = new em_empleado(link: $link);
        $this->modelo = $modelo;

        $this->titulo_lista = 'Empleados';

        $this->controlador_tg_empleado_sucursal = new controlador_tg_empleado_sucursal(
            link: $this->link, paths_conf: $paths_conf);

        $this->controlador_nom_conf_empleado = new controlador_nom_conf_empleado(
            link: $this->link, paths_conf: $paths_conf);

        $this->asignar_propiedad(identificador: 'cat_sat_regimen_fiscal_id', propiedades: ['cols'=> 8]);
        $this->asignar_propiedad(identificador: 'im_registro_patronal_id', propiedades: ['cols'=> 12]);
        $this->asignar_propiedad(identificador: 'fecha_inicio_rel_laboral', propiedades: ['cols'=> 8]);
        $this->asignar_propiedad(identificador: 'curp', propiedades: ['cols'=> 5]);
        $this->asignar_propiedad(identificador: 'codigo', propiedades: ['place_holder'=> 'Codigo']);
        $this->asignar_propiedad(identificador: 'nombre', propiedades: ['place_holder'=> 'Nombre']);
        $this->asignar_propiedad(identificador: 'ap', propiedades: ['place_holder'=> 'Apellido Paterno']);
        $this->asignar_propiedad(identificador: 'am', propiedades: ['place_holder'=> 'Apellido Materno']);
        $this->asignar_propiedad(identificador: 'telefono', propiedades: ['place_holder'=> 'Telefono']);
        $this->asignar_propiedad(identificador: 'rfc', propiedades: ['place_holder'=> 'RFC']);
        $this->asignar_propiedad(identificador: 'nss', propiedades: ['place_holder'=> 'NSS']);
        $this->asignar_propiedad(identificador: 'curp', propiedades: ['place_holder'=> 'CURP']);
        $this->asignar_propiedad(identificador: 'fecha_inicio_rel_laboral',
            propiedades: ['place_holder'=> 'Fecha Inicio Relacion Laboral']);
        $this->controlador_em_anticipo->asignar_propiedad(identificador: 'monto',
            propiedades: ['place_holder'=> 'Monto']);
        $this->controlador_em_cuenta_bancaria->asignar_propiedad(identificador: 'clabe',
            propiedades: ['place_holder'=> 'CLABE']);

        $this->asignar_propiedad(identificador: 'filtro_fecha_inicio', propiedades: ['place_holder'=> 'Fecha Inicio',
            'cols' => 12]);
        $this->asignar_propiedad(identificador: 'filtro_fecha_final', propiedades: ['place_holder'=> 'Fecha Final',
            'cols' => 12]);


    }


        public function asigna_configuracion_nomina(bool $header, bool $ws = false): array|stdClass
    {
        $alta = $this->controlador_nom_conf_empleado->alta(header: false);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar template', data: $alta, header: $header, ws: $ws);
        }

        $this->controlador_nom_conf_empleado->asignar_propiedad(identificador: 'em_empleado_id',
            propiedades: ["id_selected" => $this->registro_id, "disabled" => true,
                "filtro" => array('em_empleado.id' => $this->registro_id)]);

        $this->controlador_nom_conf_empleado->asignar_propiedad(identificador: 'em_cuenta_bancaria_id',
            propiedades: ["id_selected" => $this->registro_id]);

        $this->controlador_nom_conf_empleado->asignar_propiedad(identificador: 'nom_conf_nomina_id',
            propiedades: ["id_selected" => $this->registro_id]);

        $this->inputs = $this->controlador_nom_conf_empleado->genera_inputs(
            keys_selects:  $this->controlador_nom_conf_empleado->keys_selects);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al generar inputs', data: $this->inputs);
            print_r($error);
            die('Error');
        }

        $nom_conf_empleado = (new nom_conf_empleado($this->link))->get_configuraciones_empleado(
            em_cuenta_bancaria_id: $this->registro_id);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener conf empleado',data:  $nom_conf_empleado,
                header: $header,ws:$ws);
        }

        foreach ($nom_conf_empleado->registros as $indice => $conf_empleado) {
            $empleado_sucursal_r = $this->data_asigna_conf_nomina_btn(conf_nomina: $conf_empleado);
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al asignar botones', data: $empleado_sucursal_r,
                    header: $header, ws: $ws);
            }
            $nom_conf_empleado->registros[$indice] = $empleado_sucursal_r;
        }
        $this->nom_conf_empleado = $nom_conf_empleado;

        return $this->inputs;
    }

    public function asigna_sucursal(bool $header, bool $ws = false): array|stdClass
    {
        $alta = $this->controlador_tg_empleado_sucursal->alta(header: false);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar template', data: $alta, header: $header, ws: $ws);
        }

        $this->controlador_tg_empleado_sucursal->asignar_propiedad(identificador: 'em_empleado_id',
            propiedades: ["id_selected" => $this->registro_id, "disabled" => true,
                "filtro" => array('em_empleado.id' => $this->registro_id)]);

        $this->controlador_tg_empleado_sucursal->asignar_propiedad(identificador: 'com_sucursal_id',
            propiedades: ["label" => 'Sucursal Cliente']);

        $this->controlador_tg_empleado_sucursal->asignar_propiedad(identificador: 'com_sucursal',propiedades: array());

        $this->inputs = $this->controlador_tg_empleado_sucursal->genera_inputs(
            keys_selects:  $this->controlador_tg_empleado_sucursal->keys_selects);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al generar inputs', data: $this->inputs);
            print_r($error);
            die('Error');
        }

        $tg_empleado_sucursal = (new tg_empleado_sucursal($this->link))->get_tg_empleado_sucursal_empleado(
            em_empleado_id: $this->registro_id);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener anticipos',data:  $tg_empleado_sucursal,
                header: $header,ws:$ws);
        }

        foreach ($tg_empleado_sucursal->registros as $indice => $empleado_sucursal) {
            $empleado_sucursal_r = $this->data_empleado_sucursal_btn(empleado_sucursal: $empleado_sucursal);
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al asignar botones', data: $empleado_sucursal_r,
                    header: $header, ws: $ws);
            }
            $tg_empleado_sucursal->registros[$indice] = $empleado_sucursal_r;
        }
        $this->tg_empleado_sucursal = $tg_empleado_sucursal;

        return $this->inputs;
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


}
