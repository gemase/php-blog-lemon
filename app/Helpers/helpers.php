<?php
use App\Libraries\Session;
use App\Models\Usuario;

/**
 * Determina si el usuario esta autenticado.
 * @return boolean true: Esta autenticado,
 * false: No esta autenticado.
 */
function isAutenticado() {
    return Session::has(Session::ID_USUARIO);
}

/**
 * Intancia de Usuario autenticado.
 * @return Usuario|null
 */
function getInsSysUsuario() {
    if (isAutenticado()) {
        $id = Session::get(Session::ID_USUARIO);
        $_Usuario = Usuario::load($id);
        if ($_Usuario instanceof Usuario) {
            return $_Usuario;
        }
    }
    return null;
}

/**
 * Redirecciona a la vista enviada.
 * @param string $vista
 * @return mixed
 */
function redirecciona(string $vista = null) {
    header('location: ' . URLROOT . '/' . $vista);
    exit;
}

/**
 * Muestra mensaje de alerta.
 * @param string $texto Texto ó mensaje de la alerta.
 * @param string $tipo Tipo de alerta, ej: alert-success, 
 * alert-danger, alert-warning, alert-info.
 * @return void
 */
function muestraAlerta($texto, $tipo = 'alert-danger') {
    ob_start(); ?>
    <div class="row justify-content-md-center">
        <div class="col-md-auto">
            <div class="alert <?=$tipo?> p-2 text-center alert-dismissible fade show" role="alert">
                <?=$texto?>
                <button type="button" class="border-0 bg-transparent" data-bs-dismiss="alert" aria-label="Close">x</button>
            </div>
        </div>
    </div><?php
    echo ob_get_clean();
}
