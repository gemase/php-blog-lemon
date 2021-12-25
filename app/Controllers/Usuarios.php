<?php
use App\Libraries\Request;
use App\Libraries\Session;
use App\Libraries\TokenCSRF;
use App\Libraries\Paginacion;
use App\Models\Perfil;
use App\Models\Usuario;
use App\Models\Validador;

class Usuarios {
    //Vista: Perfil público del usuario.
    public function index($usuario) {
        $_Usuario = null;
        if (isAutenticado() && getInsSysUsuario()->getUsuario() == trim($usuario)) {
            $_Usuario = getInsSysUsuario();
        } else {
            $_Usuario = Usuario::getInsByUsuario($usuario);
        }
        if (!$_Usuario instanceof Usuario) redirecciona();
        require_once APPROOT . '/Views/Usuarios/index.php';
    }

    //Vista: Catálogo de usuarios.
    public function listar($numeroPagina = 1) {
        try {
            if (!isAutenticado()) redirecciona();

            $_SysUsuario = getInsSysUsuario();
            $tienePermisoSoloLectura = $_SysUsuario->getInsPerfil()->tienePermiso('c_usuarios', Perfil::P_LEC);
            if ($tienePermisoSoloLectura !== true) redirecciona();
            
            $tienePermisoEdicion = $_SysUsuario->getInsPerfil()->tienePermiso('c_usuarios', Perfil::P_EDI);
            $tienePermisoEdicion = $tienePermisoEdicion !== true ? false : true;
            
            $msgValidacion = null;
            $enlacePaginacion = URLROOT.'/usuarios/listar/';
            $aEstatus = Usuario::A_ESTATUS;
            $colPerfiles = Perfil::registros();
            $aFiltros = [];

            $filtroEstatus = Session::has('filtroEstatus') ? Session::get('filtroEstatus') : '';
            $filtroIdPerfil = Session::has('filtroIdPerfil') ? Session::get('filtroIdPerfil') : '';
            $filtroFechaInicial = Session::has('filtroFechaInicial') ? Session::get('filtroFechaInicial') : '';
            $filtroFechaFinal = Session::has('filtroFechaFinal') ? Session::get('filtroFechaFinal') : '';
            $filtroBuscar = Session::has('filtroBuscar') ? Session::get('filtroBuscar') : '';

            if (Request::has('post')) {
                $_Req = Request::load('post');
                $filtroEstatus = $_Req->get('estatus');
                $filtroIdPerfil = $_Req->get('idPerfil');
                $filtroFechaInicial = $_Req->get('fechaInicial');
                $filtroFechaFinal = $_Req->get('fechaFinal');
                $filtroBuscar = $_Req->get('buscar');
            }

            //Se procesan los valores a filtrar.
            if (Validador::validaVacio($filtroEstatus)) {
                $aFiltros['estatus'] = $filtroEstatus;
                Session::add('filtroEstatus', $filtroEstatus);
            } else {
                Session::remove('filtroEstatus');
            }
            if (Validador::validaVacio($filtroIdPerfil)) {
                $aFiltros['idPerfil'] = $filtroIdPerfil;
                Session::add('filtroIdPerfil', $filtroIdPerfil);
            } else {
                Session::remove('filtroIdPerfil');
            }
            if (Validador::validaVacio($filtroFechaInicial)) {
                $aFiltros['fechaInicial'] = $filtroFechaInicial;
                Session::add('filtroFechaInicial', $filtroFechaInicial);
            } else {
                Session::remove('filtroFechaInicial');
            }
            if (Validador::validaVacio($filtroFechaFinal)) {
                $aFiltros['fechaFinal'] = $filtroFechaFinal;
                Session::add('filtroFechaFinal', $filtroFechaFinal);
            } else {
                Session::remove('filtroFechaFinal');
            }
            if (Validador::validaVacio($filtroBuscar)) {
                $aFiltros['buscar'] = $filtroBuscar;
                Session::add('filtroBuscar', $filtroBuscar);
            } else {
                Session::remove('filtroBuscar');
            }
            
            $totalCantReg = Usuario::cantidadRegistros($aFiltros);
            if (!Validador::validaEntero($totalCantReg)) throw new Exception($totalCantReg);

            $_Paginacion = Paginacion::load($enlacePaginacion, $totalCantReg, $numeroPagina);
            if (!$_Paginacion instanceof Paginacion) throw new \Exception($_Paginacion);

            $aFiltros['limite'] = "{$_Paginacion->inicioLimite} , {$_Paginacion->cantidadRegPorPagina}";
            $colUsuarios = Usuario::registros($aFiltros);
            if (!is_array($colUsuarios)) throw new \Exception($colUsuarios);
        } catch (\Exception $e) {
            $colUsuarios = [];
            $_Paginacion = null;
            $msgValidacion = $e->getMessage();
        }
        require_once APPROOT . '/Views/Usuarios/listar.php';
    }

    //Vista: Crear usuario.
    public function crear() {
        if (!isAutenticado()) redirecciona();
        $colPerfiles = Perfil::registros(['estatus' => Perfil::E_ACTIVO]);
        $aEstatus = Usuario::A_ESTATUS;
        require_once APPROOT . '/Views/Usuarios/crear.php';
    }

    //Vista: Crear usuario.
    public function actualizar($id) {
        if (!isAutenticado()) redirecciona();

        $_SysUsuario = getInsSysUsuario();
        $tienePermisoEdicion = $_SysUsuario->getInsPerfil()->tienePermiso('c_usuarios', Perfil::P_EDI);
        if ($tienePermisoEdicion !== true) redirecciona();

        $_Usuario = Usuario::load($id);
        if (!$_Usuario instanceof Usuario) redirecciona();
        if ($_Usuario->esProtegido()) redirecciona();
        $colPerfiles = Perfil::registros(['estatus' => Perfil::E_ACTIVO]);
        $aEstatus = Usuario::A_ESTATUS;
        $aGeneros = Usuario::A_GENEROS;
        require_once APPROOT . '/Views/Usuarios/actualizar.php';
    }

    //Vista: Crear cuenta
    public function registro() {
        if (isAutenticado()) redirecciona();
        require_once APPROOT . '/Views/Usuarios/registro.php';
    }

    //Vista: Iniciar sesión
    public function login() {
        if (isAutenticado()) redirecciona();
        require_once APPROOT . '/Views/Usuarios/login.php';
    }

    //Vista: Cerrar sesión
    public function logout() {
        if (isAutenticado()) {
            Session::remove(Session::ID_USUARIO);
            session_destroy();
        }
        redirecciona();
    }

    //Vista: Ver usuario.
    public function ver($id) {
        if (!isAutenticado()) redirecciona();

        $_SysUsuario = getInsSysUsuario();
        $tienePermisoSoloLectura = $_SysUsuario->getInsPerfil()->tienePermiso('c_usuarios', Perfil::P_LEC);
        if ($tienePermisoSoloLectura !== true) redirecciona();

        $tienePermisoEdicion = $_SysUsuario->getInsPerfil()->tienePermiso('c_usuarios', Perfil::P_EDI);
        $tienePermisoEdicion = $tienePermisoEdicion !== true ? false : true;

        $_Usuario = Usuario::load($id);
        if (!$_Usuario instanceof Usuario) redirecciona();
        require_once APPROOT . '/Views/Usuarios/ver.php';
    }

    //Vista: Editar perfil del usuario.
    public function editar() {
        if (!isAutenticado()) redirecciona();
        $_Usuario = getInsSysUsuario();
        $aGeneros = Usuario::A_GENEROS;
        require_once APPROOT . '/Views/Usuarios/editar.php';
    }

    //Petición post: Registra ó crea un usuario (publica).
    public function postNuevo() {
        try {
            if (!Request::has('post')) redirecciona();
            $_RequServer = Request::load('server');
            if (!TokenCSRF::verificaToken($_RequServer->get('HTTP_CSRF_TOKEN'))) {
                TokenCSRF::msgTokenNoValido();
            }
            $_Perfil = Perfil::getInsPerfilAutor();
            $_Req = Request::load('post');
            $aDatos = [
                'nombre' => $_Req->get('nombre'),
                'apellido' => $_Req->get('apellido'),
                'usuario' => $_Req->get('usuario'),
                'correo' => $_Req->get('correo'),
                'clave' => $_Req->get('clave'),
                'claveConfirmacion' => $_Req->get('claveConfirmacion')
            ];
            $result = Usuario::crea($_Perfil, $aDatos);
            if ($result !== true) throw new Exception($result);
            $enlace = URLROOT . '/usuarios/login';
            $msg = 'Tu cuenta ha sido creada, ahora puedes <a href="'.$enlace.'">Iniciar sesión</a>.';
            echo json_encode([
                'tipoAlerta' => 'alert-warning', 'textoAlerta' => $msg, 
                'limpiaForm' => true, 'nuevoToken' => TokenCSRF::creaToken()
            ]);
        } catch (\Exception $e) {
            echo json_encode([
                'tipoAlerta' => 'alert-danger', 'textoAlerta' => $e->getMessage(), 
                'nuevoToken' => TokenCSRF::creaToken()
            ]);
        }
    }

    //Petición post: Inicia sesión del usuario (publica).
    public function postLogin() {
        try {
            if (!Request::has('post')) redirecciona();
            $_RequServer = Request::load('server');
            if (!TokenCSRF::verificaToken($_RequServer->get('HTTP_CSRF_TOKEN'))) {
                TokenCSRF::msgTokenNoValido();
            }
            $_Req = Request::load('post');
            $result = Usuario::iniciaSesion($_Req->get('usuarioCorreo'), $_Req->get('clave'));
            if ($result !== true) throw new Exception($result);
            echo json_encode(['url' => true]);
        } catch (\Exception $e) {
            echo json_encode([
                'tipoAlerta' => 'alert-danger', 'textoAlerta' => $e->getMessage(), 
                'nuevoToken' => TokenCSRF::creaToken()
            ]);
        }
    }

    //Petición post: Edita información general de usuario (mi perfil de usuario).
    public function postEditaInfGeneral() {
        try {
            if (!Request::has('post')) redirecciona();
            $_RequServer = Request::load('server');
            if (!TokenCSRF::verificaToken($_RequServer->get('HTTP_CSRF_TOKEN'))) {
                TokenCSRF::msgTokenNoValido();
            }
            $_Usuario = getInsSysUsuario();
            $_Req = Request::load('post');
            $aDatos = [
                'nombre' => $_Req->get('nombre'),
                'apellido' => $_Req->get('apellido'),
                'pais' => $_Req->get('pais'),
                'ciudad' => $_Req->get('ciudad'),
                'genero' => $_Req->get('genero'),
                'fechaNacimiento' => $_Req->get('fechaNacimiento'),
                'biografia' => $_Req->get('biografia')
            ];
            $result = $_Usuario->editaInformacionGeneral($_Usuario, $aDatos);
            if ($result !== true) throw new Exception($result);
            $msg = 'Tu información se editó correctamente.';
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

    //Petición post: Edita información de cuenta del usuario (mi perfil de usuario).
    public function postEditaInfCuenta() {
        try {
            if (!Request::has('post')) redirecciona();
            $_RequServer = Request::load('server');
            if (!TokenCSRF::verificaToken($_RequServer->get('HTTP_CSRF_TOKEN'))) {
                TokenCSRF::msgTokenNoValido();
            }
            $_Usuario = getInsSysUsuario();
            $_Req = Request::load('post');
            $aDatos = [
                'usuario' => $_Req->get('usuario'),
                'correo' => $_Req->get('correo')
            ];
            $result = $_Usuario->editaInformacionCuenta($_Usuario, $aDatos);
            if ($result !== true) throw new Exception($result);
            $msg = 'Tu información se editó correctamente.';
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

    //Petición post: Edita información de contrasela del usuario (mi perfil de usuario).
    public function postEditaInfClave() {
        try {
            if (!Request::has('post')) redirecciona();
            $_RequServer = Request::load('server');
            if (!TokenCSRF::verificaToken($_RequServer->get('HTTP_CSRF_TOKEN'))) {
                TokenCSRF::msgTokenNoValido();
            }
            $_Usuario = getInsSysUsuario();
            $_Req = Request::load('post');
            $aDatos = [
                'claveActual' => $_Req->get('claveActual'),
                'claveNueva' => $_Req->get('claveNueva'),
                'claveConfirmacion' => $_Req->get('claveConfirmacion')
            ];
            $result = $_Usuario->editaInformacionClave($_Usuario, $aDatos);
            if ($result !== true) throw new Exception($result);
            $msg = 'Tu información se editó correctamente.';
            echo json_encode([
                'tipoAlerta' => 'alert-warning', 'textoAlerta' => $msg, 
                'nuevoToken' => TokenCSRF::creaToken(), 'limpiaForm' => true
            ]);
        } catch (\Exception $e) {
            echo json_encode([
                'tipoAlerta' => 'alert-danger', 'textoAlerta' => $e->getMessage(), 
                'nuevoToken' => TokenCSRF::creaToken()
            ]);
        }
    }

    //Petición post: Registra ó crea un usuario (acceso a catálogo de usuarios).
    public function postCrear() {
        try {
            if (!Request::has('post')) redirecciona();
            $_RequServer = Request::load('server');
            if (!TokenCSRF::verificaToken($_RequServer->get('HTTP_CSRF_TOKEN'))) {
                TokenCSRF::msgTokenNoValido();
            }

            $_SysUsuario = getInsSysUsuario();
            $tienePermiso = $_SysUsuario->getInsPerfil()->tienePermiso('c_usuarios', Perfil::P_EDI);
            if ($tienePermiso !== true) throw new \Exception($tienePermiso);

            $_Req = Request::load('post');
            $_Perfil = Perfil::load($_Req->get('idPerfil'));
            if (!$_Perfil instanceof Perfil) throw new \Exception($_Perfil);
            
            $aDatos = [
                'nombre' => $_Req->get('nombre'),
                'apellido' => $_Req->get('apellido'),
                'usuario' => $_Req->get('usuario'),
                'correo' => $_Req->get('correo'),
                'clave' => $_Req->get('clave'),
                'estatus' => $_Req->get('estatus'),
                'claveConfirmacion' => $_Req->get('claveConfirmacion')
            ];
            $result = Usuario::crea($_Perfil, $aDatos);
            if ($result !== true) throw new Exception($result);
            $enlace = URLROOT . '/usuarios/login';
            $msg = 'El usuario fue creado correctamente.';
            echo json_encode([
                'tipoAlerta' => 'alert-warning', 'textoAlerta' => $msg, 
                'limpiaForm' => true, 'nuevoToken' => TokenCSRF::creaToken()
            ]);
        } catch (\Exception $e) {
            echo json_encode([
                'tipoAlerta' => 'alert-danger', 'textoAlerta' => $e->getMessage(), 
                'nuevoToken' => TokenCSRF::creaToken()
            ]);
        }
    }

    //Petición post: Edita información general de usuario (acceso a catálogo de usuarios).
    public function postEditaInfGeneralAccesoCatalogo() {
        try {
            if (!Request::has('post')) redirecciona();
            $_RequServer = Request::load('server');
            if (!TokenCSRF::verificaToken($_RequServer->get('HTTP_CSRF_TOKEN'))) {
                TokenCSRF::msgTokenNoValido();
            }
            $_SysUsuario = getInsSysUsuario();
            $tienePermiso = $_SysUsuario->getInsPerfil()->tienePermiso('c_usuarios', Perfil::P_EDI);
            if ($tienePermiso !== true) throw new \Exception($tienePermiso);

            $_Req = Request::load('post');
            $_Usuario = Usuario::load($_Req->get('id'));
            if (!$_Usuario instanceof Usuario) throw new Exception($_Usuario);

            $aDatos = [
                'nombre' => $_Req->get('nombre'),
                'apellido' => $_Req->get('apellido'),
                'pais' => $_Req->get('pais'),
                'ciudad' => $_Req->get('ciudad'),
                'genero' => $_Req->get('genero'),
                'fechaNacimiento' => $_Req->get('fechaNacimiento'),
                'biografia' => $_Req->get('biografia')
            ];
            $result = $_Usuario->editaInformacionGeneral($_SysUsuario, $aDatos);
            if ($result !== true) throw new Exception($result);
            $msg = 'El usuario fue editado correctamente.';
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

    //Petición post: Edita información de cuenta del usuario (acceso a catálogo de usuarios).
    public function postEditaInfCuentaAccesoCatalogo() {
        try {
            if (!Request::has('post')) redirecciona();
            $_RequServer = Request::load('server');
            if (!TokenCSRF::verificaToken($_RequServer->get('HTTP_CSRF_TOKEN'))) {
                TokenCSRF::msgTokenNoValido();
            }
            $_SysUsuario = getInsSysUsuario();
            $tienePermiso = $_SysUsuario->getInsPerfil()->tienePermiso('c_usuarios', Perfil::P_EDI);
            if ($tienePermiso !== true) throw new \Exception($tienePermiso);

            $_Req = Request::load('post');
            $_Usuario = Usuario::load($_Req->get('id'));
            if (!$_Usuario instanceof Usuario) throw new Exception($_Usuario);
            $_Perfil = Perfil::load($_Req->get('idPerfil'));
            if (!$_Perfil instanceof Perfil) throw new \Exception($_Perfil);

            $aDatos = [
                'usuario' => $_Req->get('usuario'),
                'correo' => $_Req->get('correo'),
                'estatus' => $_Req->get('estatus')
            ];
            $result = $_Usuario->editaInformacionCuenta($_SysUsuario, $aDatos, $_Perfil);
            if ($result !== true) throw new Exception($result);
            $msg = 'El usuario fue editado correctamente.';
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

    //Petición post: Edita información de contraseña del usuario (acceso a catálogo de usuarios).
    public function postEditaInfClaveAccesoCatalogo() {
        try {
            if (!Request::has('post')) redirecciona();
            $_RequServer = Request::load('server');
            if (!TokenCSRF::verificaToken($_RequServer->get('HTTP_CSRF_TOKEN'))) {
                TokenCSRF::msgTokenNoValido();
            }
            $_SysUsuario = getInsSysUsuario();
            $tienePermiso = $_SysUsuario->getInsPerfil()->tienePermiso('c_usuarios', Perfil::P_EDI);
            if ($tienePermiso !== true) throw new \Exception($tienePermiso);

            $_Req = Request::load('post');
            $_Usuario = Usuario::load($_Req->get('id'));
            if (!$_Usuario instanceof Usuario) throw new Exception($_Usuario);
            
            $aDatos = [
                'claveNueva' => $_Req->get('claveNueva'),
                'claveConfirmacion' => $_Req->get('claveConfirmacion')
            ];
            $result = $_Usuario->editaInformacionClave($_SysUsuario, $aDatos, false);
            if ($result !== true) throw new Exception($result);
            $msg = 'El usuario fue editado correctamente.';
            echo json_encode([
                'tipoAlerta' => 'alert-warning', 'textoAlerta' => $msg, 
                'nuevoToken' => TokenCSRF::creaToken(), 'limpiaForm' => true
            ]);
        } catch (\Exception $e) {
            echo json_encode([
                'tipoAlerta' => 'alert-danger', 'textoAlerta' => $e->getMessage(), 
                'nuevoToken' => TokenCSRF::creaToken()
            ]);
        }
    }
}