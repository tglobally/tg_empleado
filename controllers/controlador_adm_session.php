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
use gamboamartin\system\links_menu;
use JsonException;
use PDO;
use stdClass;


class controlador_adm_session extends \gamboamartin\controllers\controlador_adm_session {
    public bool $existe_msj = false;
    public string $include_menu = '';
    public string $mensaje_html = '';

    public array $secciones = array("em_empleado", "em_anticipo", "em_abono_anticipo", "em_cuenta_bancaria", "em_registro_patronal",
        "em_tipo_anticipo", "em_tipo_descuento", "em_tipo_abono_anticipo", "em_metodo_calculo" , "org_puesto" , "org_tipo_puesto");
    public array $links_catalogos = array();

    public stdClass $links;

    public function __construct(PDO $link, stdClass $paths_conf = new stdClass())
    {
        parent::__construct($link, $paths_conf);

        $this->links = (new links_menu(link: $link, registro_id: $this->registro_id))->genera_links($this);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al inicializar links', data: $this->links);
            print_r($error);
            die('Error');
        }

        $this->links_catalogos["em_empleado"]["titulo"] = "Empleados";
        $this->links_catalogos["em_empleado"]["subtitulo"] = "Catálogo";
        $this->links_catalogos["em_anticipo"]["titulo"] = "Anticipos";
        $this->links_catalogos["em_anticipo"]["subtitulo"] = "Catálogo";
        $this->links_catalogos["em_abono_anticipo"]["titulo"] = "Abonos";
        $this->links_catalogos["em_abono_anticipo"]["subtitulo"] = "Catálogo";
        $this->links_catalogos["em_cuenta_bancaria"]["titulo"] = "Cuentas Bancarias";
        $this->links_catalogos["em_cuenta_bancaria"]["subtitulo"] = "Catálogo";
        $this->links_catalogos["em_registro_patronal"]["titulo"] = "Registros Patronales";
        $this->links_catalogos["em_registro_patronal"]["subtitulo"] = "Catálogo";
        $this->links_catalogos["em_tipo_anticipo"]["titulo"] = "Tipos Anticipos";
        $this->links_catalogos["em_tipo_anticipo"]["subtitulo"] = "Catálogo";
        $this->links_catalogos["em_tipo_descuento"]["titulo"] = "Tipos Descuentos";
        $this->links_catalogos["em_tipo_descuento"]["subtitulo"] = "Catálogo";
        $this->links_catalogos["em_tipo_abono_anticipo"]["titulo"] = "Tipo Abonos";
        $this->links_catalogos["em_tipo_abono_anticipo"]["subtitulo"] = "Catálogo";
        $this->links_catalogos["em_metodo_calculo"]["titulo"] = "Métodos Calculo";
        $this->links_catalogos["em_metodo_calculo"]["subtitulo"] = "Catálogo";

        $this->links_catalogos["org_puesto"]["titulo"] = "Puestos";
        $this->links_catalogos["org_puesto"]["subtitulo"] = "Catálogo";
        $this->links_catalogos["org_tipo_puesto"]["titulo"] = "Tipo Puestos";
        $this->links_catalogos["org_tipo_puesto"]["subtitulo"] = "Catálogo";
    }

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

    public function get_link(string $seccion, string $accion = "lista"): array|string
    {
        if (!property_exists($this->links, $seccion)) {
            $error = $this->errores->error(mensaje: "Error no existe la seccion: $seccion", data: $seccion);
            print_r($error);
            die('Error');
        }

        if (!property_exists($this->links->$seccion, $accion)) {
            $error = $this->errores->error(mensaje: 'Error no existe la accion', data: $accion);
            print_r($error);
            die('Error');
        }

        return $this->links->$seccion->$accion;
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

        $this->links_catalogos = $this->inicializar_links();
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al inicializar links', data: $this->links_catalogos);
        }

        $this->include_menu = (new generales())->path_base;
        $this->include_menu .= 'templates/inicio.php';

        return $template;
    }

    public function inicializar_links(): array
    {
        foreach ($this->secciones as $link => $valor){

            $seccion = $valor;
            $accion = "lista";

            if (!is_numeric($link)){
                $seccion = $link;
                $accion = $valor;
            }

            if (!array_key_exists($seccion,$this->links_catalogos)){
                $this->links_catalogos[$seccion] = array();
            }

            if (!array_key_exists("titulo",$this->links_catalogos[$seccion])){
                $this->links_catalogos[$seccion]["titulo"] = $seccion;
            }

            if (!array_key_exists("subtitulo",$this->links_catalogos[$seccion])){
                $this->links_catalogos[$seccion]["subtitulo"] = $accion;
            }

            $this->links_catalogos[$seccion]["link"] = $this->get_link(seccion: $seccion,accion: $accion);
            if(errores::$error){
                return $this->errores->error(mensaje: 'Error al obtener link', data: $this->links);
            }
        }
        return $this->links_catalogos;
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
