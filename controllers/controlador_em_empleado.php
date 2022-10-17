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
}
