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
            $msgValidacion = null;
            $enlacePaginacion = URLROOT.'/usuarios/listar/';

            $totalCantReg = Usuario::cantidadRegistros();
            if (!Validador::validaEntero($totalCantReg)) throw new Exception($totalCantReg);

            $_Paginacion = Paginacion::load($enlacePaginacion, $totalCantReg, $numeroPagina);
            if (!$_Paginacion instanceof Paginacion) throw new \Exception($_Paginacion);

            $aFiltros = ['limite' => "{$_Paginacion->inicioLimite} , {$_Paginacion->cantidadRegPorPagina}"];
            $colUsuarios = Usuario::registros($aFiltros);
            if (!is_array($colUsuarios)) throw new \Exception($colUsuarios);
        } catch (\Exception $e) {
            $colUsuarios = [];
            $_Paginacion = null;
            $msgValidacion = $e->getMessage();
        }
        require_once APPROOT . '/Views/Usuarios/listar.php';
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

    //Vista: Editar perfil del usuario.
    public function editar() {
        if (!isAutenticado()) redirecciona();
        $_Usuario = getInsSysUsuario();
        $aGeneros = Usuario::A_GENEROS;
        require_once APPROOT . '/Views/Usuarios/editar.php';
    }

    //Petición post: Registra ó crea un usuario.
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

    //Petición post: Inicia sesión del usuario.
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

    //Petición post: Edita información general de usuario.
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

    //Petición post: Edita información de cuenta del usuario.
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

    //Petición post: Edita información de contrasela del usuario.
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
}