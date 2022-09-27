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

        $this->asignar_propiedad(identificador: 'im_registro_patronal_id', propiedades: ['cols'=> 12]);
        $this->asignar_propiedad(identificador: 'em_empleado_id', propiedades: ['cols'=> 8]);
        $this->asignar_propiedad(identificador: 'em_tipo_anticipo_id', propiedades: ['cols'=> 4]);
    }
}
