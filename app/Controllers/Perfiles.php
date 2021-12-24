<?php
use App\Libraries\Request;
use App\Libraries\TokenCSRF;
use App\Libraries\Paginacion;
use App\Libraries\Session;
use App\Models\Perfil;
use App\Models\Validador;

class Perfiles {
    //Vista: Catálogo de perfiles.
    public function index($numeroPagina = 1) {
        try {
            if (!isAutenticado()) redirecciona();

            $_SysUsuario = getInsSysUsuario();
            $tienePermisoSoloLectura = $_SysUsuario->getInsPerfil()->tienePermiso('c_perfiles', Perfil::P_LEC);
            if ($tienePermisoSoloLectura !== true) redirecciona();
            
            $tienePermisoEdicion = $_SysUsuario->getInsPerfil()->tienePermiso('c_perfiles', Perfil::P_EDI);
            $tienePermisoEdicion = $tienePermisoEdicion !== true ? false : true;
            
            $msgValidacion = '';
            $enlacePaginacion = URLROOT.'/perfiles/';
            $aEstatus = Perfil::A_ESTATUS;
            $aFiltros = [];

            $filtroEstatus = Session::has('filtroEstatus') ? Session::get('filtroEstatus') : '';
            $filtroBuscar = Session::has('filtroBuscar') ? Session::get('filtroBuscar') : '';

            if (Request::has('post')) {
                $_Req = Request::load('post');
                $filtroEstatus = $_Req->get('estatus');
                $filtroBuscar = $_Req->get('buscar');
            }

            //Se procesan los valores a filtrar.
            if (Validador::validaVacio($filtroEstatus)) {
                $aFiltros['estatus'] = $filtroEstatus;
                Session::add('filtroEstatus', $filtroEstatus);
            } else {
                Session::remove('filtroEstatus');
            }
            if (Validador::validaVacio($filtroBuscar)) {
                $aFiltros['buscar'] = $filtroBuscar;
                Session::add('filtroBuscar', $filtroBuscar);
            } else {
                Session::remove('filtroBuscar');
            }

            $totalCantReg = Perfil::cantidadRegistros($aFiltros);
            if (!Validador::validaEntero($totalCantReg)) throw new Exception($totalCantReg);

            $_Paginacion = Paginacion::load($enlacePaginacion, $totalCantReg, $numeroPagina);
            if (!$_Paginacion instanceof Paginacion) throw new \Exception($_Paginacion);

            $aFiltros['limite'] = "{$_Paginacion->inicioLimite} , {$_Paginacion->cantidadRegPorPagina}";
            $colPerfiles = Perfil::registros($aFiltros);
            if (!is_array($colPerfiles)) throw new \Exception($colPerfiles);
        } catch (\Exception $e) {
            $colPerfiles = [];
            $_Paginacion = null;
            $msgValidacion = $e->getMessage();
        }
        require_once APPROOT . '/Views/Perfiles/index.php';
    }

    //Vista: Crear perfil.
    public function crear() {
        if (!isAutenticado()) redirecciona();

        $_SysUsuario = getInsSysUsuario();
        $tienePermisoEdicion = $_SysUsuario->getInsPerfil()->tienePermiso('c_perfiles', Perfil::P_EDI);
        if ($tienePermisoEdicion !== true) redirecciona();

        $aEstatus = Perfil::A_ESTATUS;
        $aPermisos = Perfil::A_PER;
        require_once APPROOT . '/Views/Perfiles/crear.php';
    }

    //Vista: Editar perfil.
    public function editar($id) {
        if (!isAutenticado()) redirecciona();

        $_SysUsuario = getInsSysUsuario();
        $tienePermisoEdicion = $_SysUsuario->getInsPerfil()->tienePermiso('c_perfiles', Perfil::P_EDI);
        if ($tienePermisoEdicion !== true) redirecciona();

        $_Perfil = Perfil::load($id);
        if (!$_Perfil instanceof Perfil) redirecciona();
        $aEstatus = Perfil::A_ESTATUS;
        $aPermisos = Perfil::A_PER;
        require_once APPROOT . '/Views/Perfiles/editar.php';
    }

    //Vista: Ver perfil.
    public function ver($id) {
        if (!isAutenticado()) redirecciona();

        $_SysUsuario = getInsSysUsuario();
        $tienePermisoSoloLectura = $_SysUsuario->getInsPerfil()->tienePermiso('c_perfiles', Perfil::P_LEC);
        if ($tienePermisoSoloLectura !== true) redirecciona();

        $tienePermisoEdicion = $_SysUsuario->getInsPerfil()->tienePermiso('c_perfiles', Perfil::P_EDI);
        $tienePermisoEdicion = $tienePermisoEdicion !== true ? false : true;

        $_Perfil = Perfil::load($id);
        if (!$_Perfil instanceof Perfil) redirecciona();
        require_once APPROOT . '/Views/Perfiles/ver.php';
    }

    //Petición post: Registra ó crea un perfil.
    public function postCrear() {
        try {
            if (!Request::has('post')) redirecciona();
            $_RequServer = Request::load('server');
            if (!TokenCSRF::verificaToken($_RequServer->get('HTTP_CSRF_TOKEN'))) {
                TokenCSRF::msgTokenNoValido();
            }
            $_SysUsuario = getInsSysUsuario();
            $tienePermiso = $_SysUsuario->getInsPerfil()->tienePermiso('c_perfiles', Perfil::P_EDI);
            if ($tienePermiso !== true) throw new \Exception($tienePermiso);

            $_Req = Request::load('post');
            $aDatos = [
                'nombre' => $_Req->get('nombre'),
                'estatus' => $_Req->get('estatus'),
                'catalogoUsuarios' => $_Req->get('catalogoUsuarios'),
                'catalogoPerfiles' => $_Req->get('catalogoPerfiles'),
                'catalogoArticulos' => $_Req->get('catalogoArticulos'),
                'catalogoCategorias' => $_Req->get('catalogoCategorias')
            ];
            $result = Perfil::crea($_SysUsuario, $aDatos);
            if ($result !== true) throw new Exception($result);
            $msg = 'El perfil fue creado correctamente.';
            echo json_encode([
                'tipoAlerta' => 'alert-warning', 'textoAlerta' => $msg, 'limpiaForm' => true, 
                'nuevoToken' => TokenCSRF::creaToken()
            ]);
        } catch (\Exception $e) {
            echo json_encode([
                'tipoAlerta' => 'alert-danger', 'textoAlerta' => $e->getMessage(), 
                'nuevoToken' => TokenCSRF::creaToken()
            ]);
        }
    }

    //Petición post: Edita perfil.
    public function postEditar() {
        try {
            if (!Request::has('post')) redirecciona();
            $_RequServer = Request::load('server');
            if (!TokenCSRF::verificaToken($_RequServer->get('HTTP_CSRF_TOKEN'))) {
                TokenCSRF::msgTokenNoValido();
            }
            $_SysUsuario = getInsSysUsuario();
            $tienePermiso = $_SysUsuario->getInsPerfil()->tienePermiso('c_perfiles', Perfil::P_EDI);
            if ($tienePermiso !== true) throw new \Exception($tienePermiso);

            $_Req = Request::load('post');
            $_Perfil = Perfil::load($_Req->get('id'));
            if (!$_Perfil instanceof Perfil) throw new \Exception($_Perfil);
            $aDatos = [
                'nombre' => $_Req->get('nombre'),
                'estatus' => $_Req->get('estatus'),
                'catalogoUsuarios' => $_Req->get('catalogoUsuarios'),
                'catalogoPerfiles' => $_Req->get('catalogoPerfiles'),
                'catalogoArticulos' => $_Req->get('catalogoArticulos'),
                'catalogoCategorias' => $_Req->get('catalogoCategorias')
            ];
            $result = $_Perfil->editaPerfil($_SysUsuario, $aDatos);
            if ($result !== true) throw new Exception($result);
            $msg = 'El perfil fue editado correctamente.';
            echo json_encode([
                'tipoAlerta' => 'alert-warning', 'textoAlerta' => $msg,  
                'nuevoToken' => TokenCSRF::creaToken()
            ]);
        } catch (\Exception $e) {
            echo json_encode([
                'tipoAlerta' => 'alert-danger', 'textoAlerta' => $e->getMessage(), 
                'nuevoToken' => TokenCSRF::creaToken()
            ]);
        }
    }
}