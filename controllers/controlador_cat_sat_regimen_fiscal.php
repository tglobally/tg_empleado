<?php
namespace tglobally\tg_empleado\controllers;


use PDO;
use stdClass;


class controlador_cat_sat_regimen_fiscal extends \controllers\controlador_cat_sat_regimen_fiscal {

    public function __construct(PDO $link, stdClass $paths_conf = new stdClass()){


        parent::__construct(link: $link, paths_conf: $paths_conf);

        $this->titulo_lista = 'Regimen fiscal';

    }


}
