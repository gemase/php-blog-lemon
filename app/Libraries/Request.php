<?php
namespace App\Libraries;

class Request {
    /**
     * Se da valor a las propiedades de la clase Request.
     * @param array $aDatos
     */
    private function __construct(array $aDatos) {
        foreach ($aDatos as $key => $value) {
            $this->{$key} = is_array($value) ? $value : trim($value);
        }
    }

    /**
     * Retorna las solicitudes encontradas.
     * @return array
     */
    private static function all() {
        $aSolicitudes = [];
        if (count($_GET) > 0) $aSolicitudes['get'] = $_GET;
        if (count($_POST) > 0) $aSolicitudes['post'] = $_POST;
        $aSolicitudes['file'] = $_FILES;
        $aSolicitudes['server'] = $_SERVER;
        return $aSolicitudes;
    }

    /**
     * Retorna instancia de clase Request.
     * @param string $tipoSolicitud Tipo de solicitud: get, post, file, server.
     * @return Request
     */
    public static function load($tipoSolicitud) {
        $aSolicitudes = self::all();
        $_Request = new self($aSolicitudes[$tipoSolicitud]);
        return $_Request;
    }

    /**
     * Determina sin la solicitud enviada existe.
     * @param string $tipoSolicitud Tipo de solicitud: get, post, file, server.
     * @return boolean true: La solicitud existe, false: No existe.
     */
    public static function has($tipoSolicitud) {
        return array_key_exists($tipoSolicitud, self::all()) ? true : false;
    }

    /**
     * Retorna una propiedad especifica de la solicitud.
     * @param string $propiedad Nombre de la propiedad.
     * @return mixed
     */
    public function get($propiedad) {
        if (!property_exists($this, $propiedad)) {
            return null;
        }
        return $this->$propiedad;
    }
}