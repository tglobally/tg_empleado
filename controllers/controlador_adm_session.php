<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace tglobally\tg_empleado\controllers;

use config\generales;
use gamboamartin\errores\errores;
use JsonException;
use stdClass;


class controlador_adm_session extends \gamboamartin\controllers\controlador_adm_session {
    public bool $existe_msj = false;
    public string $include_menu = '';
    public string $mensaje_html = '';

    public string $link_alta_tg_cte_tipo_alianza = '';
    public string $link_lista_org_tipo_puesto = '';
    public string $link_alta_tg_cte_alianza = '';
    public string $link_lista_org_puesto = '';
    public string $link_alta_com_cliente = '';
    public string $link_lista_com_cliente = '';
    public string $link_alta_em_empleado = '';
    public string $link_lista_em_empleado = '';
    public string $link_alta_em_anticipo = '';
    public string $link_lista_em_anticipo = '';
    public string $link_alta_em_tipo_anticipo = '';
    public string $link_lista_em_tipo_anticipo = '';
    public string $link_lista_em_metodo_calculo = '';
    public string $link_lista_em_tipo_descuento = '';
    public string $link_lista_em_abono_anticipo = '';
    public string $link_lista_em_tipo_abono_anticipo = '';
    public string $link_lista_em_cuenta_bancaria = '';

    /**
     * Funcion de controlador donde se ejecutaran siempre que haya un acceso denegado
     * @param bool $header Si header es true cualquier error se mostrara en el html y cortara la ejecucion del sistema
     *              En false retornara un array si hay error y un string con formato html
     * @param bool $ws Si ws es true retornara el resultado en formato de json
     * @return array vacio siempre
     */
    public function denegado(bool $header, bool $ws = false): array
    {

        return array();

    }

    /**
     * Funcion de controlador donde se ejecutaran los elementos necesarios para poder mostrar el inicio en
     *      session/inicio
     *
     * @param bool $aplica_template Si aplica template buscara el header de la base
     *              No recomendado para vistas ajustadas como esta
     * @param bool $header Si header es true cualquier error se mostrara en el html y cortara la ejecucion del sistema
     *              En false retornara un array si hay error y un string con formato html
     * @param bool $ws Si ws es true retornara el resultado en formato de json
     * @return string|array string = html array = error
     * @throws JsonException si hay error en forma ws
     */
    public function inicio(bool $aplica_template = false, bool $header = true, bool $ws = false): string|array
    {

        $template =  parent::inicio($aplica_template, false); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(mensaje:  'Error al generar template',data: $template, header: $header, ws: $ws);
        }

        $hd = "index.php?seccion=tg_cte_tipo_alianza&accion=alta&session_id=$this->session_id";
        $this->link_alta_tg_cte_tipo_alianza = $hd;
        $hd = "index.php?seccion=tg_cte_alianza&accion=alta&session_id=$this->session_id";
        $this->link_alta_tg_cte_alianza = $hd;
        $hd = "index.php?seccion=com_cliente&accion=alta&session_id=$this->session_id";
        $this->link_alta_com_cliente = $hd;
        $hd = "index.php?seccion=em_empleado&accion=alta&session_id=$this->session_id";
        $this->link_alta_em_empleado = $hd;
        $hd = "index.php?seccion=em_anticipo&accion=alta&session_id=$this->session_id";
        $this->link_alta_em_anticipo = $hd;
        $hd = "index.php?seccion=em_tipo_anticipo&accion=alta&session_id=$this->session_id";
        $this->link_alta_em_tipo_anticipo = $hd;

        $hd = "index.php?seccion=org_tipo_puesto&accion=lista&session_id=$this->session_id";
        $this->link_lista_org_tipo_puesto = $hd;
        $hd = "index.php?seccion=org_puesto&accion=lista&session_id=$this->session_id";
        $this->link_lista_org_puesto = $hd;
        $hd = "index.php?seccion=com_cliente&accion=lista&session_id=$this->session_id";
        $this->link_lista_com_cliente = $hd;
        $hd = "index.php?seccion=em_empleado&accion=lista&session_id=$this->session_id";
        $this->link_lista_em_empleado = $hd;
        $hd = "index.php?seccion=em_anticipo&accion=lista&session_id=$this->session_id";
        $this->link_lista_em_anticipo = $hd;
        $hd = "index.php?seccion=em_tipo_anticipo&accion=lista&session_id=$this->session_id";
        $this->link_lista_em_tipo_anticipo= $hd;
        $hd = "index.php?seccion=em_metodo_calculo&accion=lista&session_id=$this->session_id";
        $this->link_lista_em_metodo_calculo= $hd;
        $hd = "index.php?seccion=em_tipo_descuento&accion=lista&session_id=$this->session_id";
        $this->link_lista_em_tipo_descuento= $hd;
        $hd = "index.php?seccion=em_abono_anticipo&accion=lista&session_id=$this->session_id";
        $this->link_lista_em_abono_anticipo= $hd;
        $hd = "index.php?seccion=em_tipo_abono_anticipo&accion=lista&session_id=$this->session_id";
        $this->link_lista_em_tipo_abono_anticipo= $hd;
        $hd = "index.php?seccion=em_cuenta_bancaria&accion=lista&session_id=$this->session_id";
        $this->link_lista_em_cuenta_bancaria= $hd;

        $this->include_menu = (new generales())->path_base;
        $this->include_menu .= 'templates/inicio.php';

        return $template;
    }

    /**
     * Funcion de controlador donde se ejecutaran los elementos necesarios para la asignacion de datos de logueo
     * @param bool $header Si header es true cualquier error se mostrara en el html y cortara la ejecucion del sistema
     *              En false retornara un array si hay error y un string con formato html
     * @param bool $ws Si ws es true retornara el resultado en formato de json
     * @param string $accion_header
     * @param string $seccion_header
     * @return array string = html array = error
     *
     */
    public function loguea(bool $header, bool $ws = false, string $accion_header = 'login', string $seccion_header = 'session'): array
    {
        $loguea = parent::loguea(header: true,accion_header:  $accion_header,
            seccion_header:  $seccion_header); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(mensaje:  'Error al loguear',data: $loguea, header: $header, ws: $ws);
        }
        return $loguea;
    }


    /**
     * Funcion de controlador donde se ejecutaran los elementos de session/login
     *
     * @param bool $header Si header es true cualquier error se mostrara en el html y cortara la ejecucion del sistema
     *              En false retornara un array si hay error y un string con formato html
     * @param bool $ws Si ws es true retornara el resultado en formato de json
     * @return string|array string = html array = error
     */
    public function login(bool $header = true, bool $ws = false): stdClass|array
    {
        $login = parent::login($header, $ws); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(mensaje:  'Error al generar template',data: $login, header: $header, ws: $ws);
        }

        $this->mensaje_html = '';
        if(isset($_GET['mensaje']) && $_GET['mensaje'] !==''){
            $mensaje = trim($_GET['mensaje']);
            if($mensaje !== ''){
                $this->mensaje_html = $mensaje;
                $this->existe_msj = true;
            }
        }

        $this->include_menu .= 'templates/login.php';

        return $login;

    }



}
