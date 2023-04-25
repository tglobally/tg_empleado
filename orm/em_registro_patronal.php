<?php

namespace tglobally\tg_empleado\models;

use base\orm\modelo;
use gamboamartin\errores\errores;
use gamboamartin\im_registro_patronal\models\im_conf_pres_empresa;
use gamboamartin\im_registro_patronal\models\im_conf_prestaciones;
use PDO;
use stdClass;

class em_registro_patronal extends \gamboamartin\empleado\models\em_registro_patronal {

    public function __construct(PDO $link){
        parent::__construct(link: $link);
    }

    public function alta_bd(array $keys_integra_ds = array('codigo', 'descripcion')): array|stdClass
    {
        $alta_bd = parent::alta_bd($keys_integra_ds);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al dar de alta empleado',data: $alta_bd);
        }

        $registro = $this->registro(registro_id: $alta_bd->registro_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener datos de registro patronal', data: $registro);
        }

        $filtro['org_empresa.id'] = $registro['org_empresa_id'];
        $r_conf_prestaciones = (new im_conf_pres_empresa($this->link))->filtro_and(filtro: $filtro);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener im_conf_pres_empresa', data: $r_conf_prestaciones);
        }

        if ($r_conf_prestaciones->n_registros == 0) {

            $prestacion = (new im_conf_prestaciones($this->link))->filtro_and(limit: 1);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al obtener im_conf_prestaciones', data: $prestacion);
            }

            if ($prestacion->n_registros <= 0) {
                return $this->error->error(mensaje: 'Error no existe una conf. para im_conf_prestaciones', data: $prestacion);
            }

            $data['descripcion'] = $registro['org_empresa_descripcion'];
            $data['descripcion_select'] = $registro['org_empresa_descripcion_select'];
            $data['codigo'] = $registro['org_empresa_codigo'];
            $data['codigo_bis'] = $registro['org_empresa_codigo_bis'];
            $data['alias'] = $registro['org_empresa_alias'];
            $data['org_empresa_id'] = $registro['org_empresa_id'];
            $data['im_conf_prestaciones_id'] = $prestacion->registros[0]['im_conf_prestaciones_id'];

            $alta_conf = (new im_conf_pres_empresa($this->link))->alta_registro(registro: $data);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al dar de alta im_conf_pres_empresa', data: $alta_conf);
            }
        }
        return $alta_bd;
    }

}