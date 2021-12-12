<?php
use App\Libraries\Request;
use App\Libraries\Session;
use App\Libraries\Paginacion;
use App\Models\Perfil;
use App\Models\Validador;

class Perfiles {
    //Vista: Catálogo de perfiles.
    public function index($numeroPagina = 1) {
        try {
            $msgValidacion = null;
            $enlacePaginacion = URLROOT.'/perfiles/';

            $totalCantReg = Perfil::cantidadRegistros();
            if (!Validador::validaEntero($totalCantReg)) throw new Exception($totalCantReg);

            $_Paginacion = Paginacion::load($enlacePaginacion, $totalCantReg, $numeroPagina);
            if (!$_Paginacion instanceof Paginacion) throw new \Exception($_Paginacion);

            $aFiltros = ['limite' => "{$_Paginacion->inicioLimite} , {$_Paginacion->cantidadRegPorPagina}"];
            $colPerfiles = Perfil::registros($aFiltros);
            if (!is_array($colPerfiles)) throw new \Exception($colPerfiles);
        } catch (\Exception $e) {
            $colPerfiles = [];
            $_Paginacion = null;
            $msgValidacion = $e->getMessage();
        }
        require_once APPROOT . '/Views/Perfiles/index.php';
    }
}