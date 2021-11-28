<?php
namespace App\Models;

trait Validador {

    /**
     * Valida que el valor enviado sea un dato númerico entero.
     * @param mixed $valor
     * @param boolean $positivo true: Valida que el valor ademas
     * de entero sea positivo, false: Valida que solo sea entero.
     * @return bool true: Válido, false: No válido.
     */
    public static function validaEntero($valor, $positivo = false) {
        $valor = filter_var($valor, FILTER_VALIDATE_INT);
        if ($valor === false) return false;
        if ($positivo && $valor < 0) return false;
        return true;
    }

    /**
     * Valida que el valor enviado sea un correo electrónico.
     * @param mixed $valor
     * @return bool true: Válido, false: No válido.
     */
    public static function validaCorreo($valor) {
        if (!filter_var($valor, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }

    /**
     * Valida que el valor enviado tenga la longitud máxima válida.
     * @param mixed $valor
     * @param integer $longitudMax Longitud a validar.
     * @return bool true: Válido, false: No válido.
     */
    public static function validaLogMax($valor, $longitudMax) {
        //FIXME: Analizar manejo de longitud de caracteres especiales (mb_strlen)
        if (strlen($valor) > $longitudMax) {
            return false;
        }
        return true;
    }

    /**
     * Valida que el valor enviado no sea un dato vacío o nulo.
     * @param mixed $valor
     * @return bool true: Válido, false: No válido.
     */
    public static function validaVacio($valor) {
        $valor = trim($valor);
        if ($valor == '' || is_null($valor)) {
            return false;
        }
        return true;
    }

    /**
     * Valida que el valor enviado sean solo números y letras en base
     * a la cadena a comparar.
     * FIXME: Mejorar método.
     * @param mixed $valor
     * @return bool true: Válido, false: No válido.
     */
    public static function validaCadena($valor) {
        $valor = trim($valor);
        $cadena = "/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð0-9'-.()\\s]+$/";
        if (!preg_match($cadena, $valor)) {
            return false;
        }
        return true;
    }

    /**
     * Valida que el valor ingresado sea un dato con caracteres validos.
     * @param mixed $valor
     * @return bool true: Válido, false: No válido.
     */
    public static function validaCarEsp($valor) {
        $valorFil = filter_var($valor, FILTER_SANITIZE_SPECIAL_CHARS);
        if ($valor != $valorFil) return false;
        return true;
    }
}