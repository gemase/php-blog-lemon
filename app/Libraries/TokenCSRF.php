<?php
namespace App\Libraries;

class TokenCSRF {
    /**
     * Crea ó genera token.
     * @return string
     */
    public static function creaToken() {
        if (!Session::has('token')) {
            $token = base64_encode(openssl_random_pseudo_bytes(32));
            Session::add('token', $token);
        }
        return Session::get('token');
    }

    /**
     * Verifica que el token exista y sea válido.
     * @param string $token
     * @param boolean $regenerar true: Elimina el token 
     * enviado y genera uno nuevo.
     * @return bool true: Token válido, false: Token no válido.
     */
    public static function verificaToken($token, $regenerar = true) {
        if (Session::has('token') && Session::get('token') === $token) {
            if ($regenerar) {
                Session::remove('token');
            }
            return true;
        }
        return false;
    }

    /**
     * Retorna json de mensaje (alerta) cuando el token no es válido.
     * Es necesario detener el proceso (exit) ya que es respuesta para JS.
     * @return mixed
     */
    public static function msgTokenNoValido() {
        $aResp = [
            'tipoAlerta' => 'alert-danger',
            'textoAlerta' => 'Token no válido, para continuar es necesario recargar la página.'
        ];
        echo json_encode($aResp);
        exit;
    }
}