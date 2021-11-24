<?php
namespace App\Libraries;

class Session {
    //Id usuario en sesión
    const ID_USUARIO = 'ID_USUARIO';

    /**
     * Crea sesión.
     * @param string $nombre Nombre de la sesión.
     * @param string $valor Valor de la sesión.
     * @return mixed $_SESSION
     * @throws Exception Mensaje de validación.
     */
    public static function add($nombre, $valor) {
        if ($nombre != '' && $valor != '') {
            return $_SESSION[$nombre] = $valor;
        }
        throw new \Exception('Sesión: El nombre y valor son requeridos.');
    }

    /**
     * Retorna el valor de la sesión.
     * @param string $nombre Nombre de la sesión.
     * @return mixed $_SESSION
     */
    public static function get($nombre) {
        return $_SESSION[$nombre];
    }

    /**
     * Determina si existe la sesión.
     * @param string $nombre Nombre de la sesión.
     * @return boolean true: La sesión existe, false: No existe.
     * @throws Exception Mensaje de validación.
     */
    public static function has($nombre) {
        if ($nombre != '' && !empty($nombre)) {
            return isset($_SESSION[$nombre]) ? true : false;
        }
        throw new \Exception('Sesión: El nombre es requerido.');
    }

    /**
     * Elimina la sesión.
     * @param string $nombre Nombre de la sesión.
     */
    public static function remove($nombre) {
        if (self::has($nombre)) {
            unset($_SESSION[$nombre]);
        }
    }
}