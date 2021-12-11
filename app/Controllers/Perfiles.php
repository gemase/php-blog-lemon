<?php

use App\Libraries\Request;
use App\Libraries\Session;
use App\Models\Perfil;

class Perfiles {
    //Vista: Catálogo de perfiles.
    public function index() {
        $colPerfiles = Perfil::registros();
        require_once APPROOT . '/Views/Perfiles/index.php';
    }
}