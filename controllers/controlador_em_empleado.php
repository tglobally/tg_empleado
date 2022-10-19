<?php
namespace tglobally\tg_empleado\controllers;


use gamboamartin\errores\errores;
use models\em_empleado;
use models\im_conf_pres_empresa;
use PDO;
use stdClass;
use tglobally\template_tg\html;
use Throwable;

class controlador_em_empleado extends \gamboamartin\empleado\controllers\controlador_em_empleado {
    public controlador_tg_empleado_sucursal $controlador_tg_empleado_sucursal;

    public function __construct(PDO $link, stdClass $paths_conf = new stdClass())
    {
        $html_base = new html();
        parent::__construct(link: $link, html: $html_base);

        $modelo = new em_empleado(link: $link);
        $this->modelo = $modelo;

        $this->titulo_lista = 'Empleados';



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

        $this->inputs = $this->controlador_tg_empleado_sucursal->genera_inputs(
            keys_selects:  $this->controlador_tg_empleado_sucursal->keys_selects);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al generar inputs', data: $this->inputs);
            print_r($error);
            die('Error');
        }
/*
        $cuentas_bancarias = (new em_asigna_sucursal($this->link))->get_cuentas_bancarias_empleado(
            em_empleado_id: $this->registro_id);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener anticipos',data:  $cuentas_bancarias,
                header: $header,ws:$ws);
        }

        foreach ($cuentas_bancarias->registros as $indice => $asigna_sucursal) {
            $asigna_sucursal = $this->data_asigna_sucursal_btn(asigna_sucursal: $asigna_sucursal);
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al asignar botones', data: $asigna_sucursal, header: $header, ws: $ws);
            }
            $cuentas_bancarias->registros[$indice] = $asigna_sucursal;
        }
        $this->cuentas_bancarias = $cuentas_bancarias;
*/
        return $this->inputs;
    }

    public function cuenta_bancaria_alta_bd(bool $header, bool $ws = false)
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
            echo json_encode($alta, JSON_THROW_ON_ERROR);
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
}
