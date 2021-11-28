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