<?php
namespace App\Libraries;

class Core {
    /**
     * Controlador a ejecutar.
     * Controlador predeterminado: Inicio.
     * @var string
     */
    private $controlador = 'Inicio';
    /**
     * Método a ejecutar.
     * Método predeterminado: index.
     * @var string
     */
    private $metodo = 'index';
    /**
     * Parametros a ejecutar.
     * @var array
     */
    private $aParametros = [];

    /**
     * Se da valor al controlador, método y parametros para
     * su ejecución.
     * @param Request|null $_Request Solicitud enviada.
     */
    public function __construct(Request $_Request = null) {
        $url = $this->getUrl($_Request);

        if (isset($url[0]) && file_exists('../app/Controllers/' . ucwords($url[0]). '.php')) {
            $this->controlador = ucwords($url[0]);
            unset($url[0]);
        } else {
            if (!empty($url)) {
                array_unshift($url, $this->controlador);
            }
        }
        
        require_once "../app/Controllers/{$this->controlador}.php";
        
        $this->controlador = new $this->controlador;
        
        if (isset($url[1])) {
            if (method_exists($this->controlador, $url[1])) {
                $this->metodo = $url[1];
                unset($url[1]);
            }
        }
        
        $this->aParametros = $url ? array_values($url) : [];
        
        //Se llama a la [clase, método] con sus parámetros [parámetro 1, parámetro 2].
        call_user_func_array([$this->controlador, $this->metodo], $this->aParametros);
    }

    /**
     * Obtiene url de la solicitud y la retorna como arreglo.
     * @param Request|null $_Request
     * @return array
     */
    private function getUrl(Request $_Request = null) {
        $url = [];
        if ($_Request instanceof Request) {
            if (!is_null($_Request->get('url'))) {
                $url = rtrim($_Request->get('url'), '/');
                $url = filter_var($_Request->get('url'), FILTER_SANITIZE_URL);
                $url = explode('/', $_Request->get('url'));
            }
        }
        return $url;
    }
}