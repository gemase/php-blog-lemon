<?php
use App\Libraries\Request;
use App\Libraries\Session;
use App\Libraries\TokenCSRF;
use App\Models\Perfil;
use App\Models\Usuario;

class Usuarios {
    //Vista: Perfil público del usuario
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

    public function editar() {
        require_once APPROOT . '/Views/Usuarios/editar.php';
    }

    //Vista: Crear cuenta
    public function registro() {
        if (Request::has('post')) {
            try {
                $_RequServer = Request::load('server');
                if (TokenCSRF::verificaToken($_RequServer->get('HTTP_CSRF_TOKEN'))) {
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
                        'tipoAlerta' => 'alert-success', 'textoAlerta' => $msg, 
                        'limpiaForm' => true, 'nuevoToken' => TokenCSRF::creaToken()
                    ]);
                } else {
                    echo TokenCSRF::msgTokenNoValido();
                }
            } catch (\Exception $e) {
                echo json_encode([
                    'tipoAlerta' => 'alert-danger', 'textoAlerta' => $e->getMessage(), 
                    'nuevoToken' => TokenCSRF::creaToken()
                ]);
            }
        } else {
            if (isAutenticado()) redirecciona();
            require_once APPROOT . '/Views/Usuarios/registro.php';
        }
    }

    //Vista: Iniciar sesión
    public function login() {
        if (Request::has('post')) {
            try {
                $_RequServer = Request::load('server');
                if (TokenCSRF::verificaToken($_RequServer->get('HTTP_CSRF_TOKEN'))) {
                    $_Req = Request::load('post');
                    $result = Usuario::iniciaSesion($_Req->get('usuarioCorreo'), $_Req->get('clave'));
                    if ($result !== true) throw new Exception($result);
                    echo json_encode(['url' => true]);
                } else {
                    echo TokenCSRF::msgTokenNoValido();
                }
            } catch (\Exception $e) {
                echo json_encode([
                    'tipoAlerta' => 'alert-danger', 'textoAlerta' => $e->getMessage(), 
                    'nuevoToken' => TokenCSRF::creaToken()
                ]);
            }
        } else {
            if (isAutenticado()) redirecciona();
            require_once APPROOT . '/Views/Usuarios/login.php';
        }
    }

    //Vista: Cerrar sesión
    public function logout() {
        if (isAutenticado()) {
            Session::remove(Session::ID_USUARIO);
            session_destroy();
        }
        redirecciona();
    }
}