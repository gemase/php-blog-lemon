<?php
namespace App\Models;

class Usuario extends Crud {
    use Validador;

    //Estatus Inactivo.
    const E_INACTIVO = 0;

    //Estatus Activo.
    const E_ACTIVO = 1;

    //Estatus del usuario.
    const A_ESTATUS = [
        self::E_INACTIVO => 'Inactivo',
        self::E_ACTIVO => 'Activo'
    ];

    //Usuario no protegido (Puede ser editado).
    const NO_PROTEGIDO = 0;

    //Usuario protegido (No se permite su edición).
    const PROTEGIDO = 1;

    /**
     * Identificador único del usuario.
     * @var integer
     */
    private $id;
    /**
     * Id perfil del usuario.
     * @var integer
     */
    private $id_perfil;
    /**
     * Nombre del usuario.
     * @var string
     */
    private $nombre;
    /**
     * Apellido del usuario.
     * @var string
     */
    private $apellido;
    /**
     * Usuario ó apodo único del usuario.
     * @var string
     */
    private $usuario;
    /**
     * Correo electrónico del usuario.
     * @var string
     */
    private $correo;
    /**
     * Contraseña del usuario.
     * @var string
     */
    private $clave;
    /**
     * Estatus del usuario.
     * @var integer
     */
    private $estatus;
    /**
     * Determina si el usuario puede ser editado.
     * @var integer
     */
    private $protegido;
    /**
     * Fecha y hora de creación del usuario.
     * @var string
     */
    private $fecha_creo;
    /**
     * Identificador único del usuario.
     * @return integer
     */
    public function getId() {
        return $this->id;
    }
    /**
     * Id perfil del usuario.
     * @return integer
     */
    public function getIdPerfil() {
        return $this->id_perfil;
    }
    /**
     * Nombre del usuario.
     * @return string
     */
    public function getNombre() {
        return $this->nombre;
    }
    /**
     * Apellido del usuario.
     * @return string
     */
    public function getApellido() {
        return $this->apellido;
    }
    /**
     * Usuario ó apodo único del usuario.
     * @return string
     */
    public function getUsuario() {
        return $this->usuario;
    }
    /**
     * Correo electrónico del usuario.
     * @return string
     */
    public function getCorreo() {
        return $this->correo;
    }
    /**
     * Contraseña del usuario.
     * @return string
     */
    public function getClave() {
        return $this->clave;
    }
    /**
     * Estatus del usuario.
     * @param boolean $muestraDes true: Retorna descripción,
     * false: Valor del campo.
     * @return string|integer
     */
    public function getEstatus($muestraDes = false) {
        if ($muestraDes) {
            return self::A_ESTATUS[$this->estatus];
        }
        return $this->estatus;
    }
    /**
     * Determina si el usuario puede ser editado.
     * @return integer
     */
    public function getProtegido() {
        return $this->protegido;
    }
    /**
     * Fecha y hora de creación del usuario.
     * @param boolean $objeto true: Instancia DateTime,
     * false: Valor del campo.
     * @return string|DateTime
     */
    public function getFechaCreo($objeto = false) {
        if ($objeto) {
            return new \DateTime($this->fecha_creo);
        }
        return $this->fecha_creo;
    }
    /**
     * Nombre de la tabla de la clase.
     * @return string
     */
    public static function getNombreTabla() {
        return 'usuario';
    }

    /**
     * Instancia Usuario.
     * @param integer $id Id usuario.
     * @return Usuario|string string: Mensaje de validación.
     */
    public static function load($id) {
        try {
            if (!self::validaVacio($id) || !self::validaEntero($id)) {
                throw new \Exception('El usuario no es válido.');
            }
            $colObj = self::registros(['id' => $id]);
            if (!is_array($colObj)) throw new \Exception($colObj);
            if (empty($colObj)) throw new \Exception('El usuario no existe.');
            return current($colObj);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Retorna colección de instancias Usuario.
     * @param array $aFiltros Campos a filtrar.
     * @return array|string string: Mensaje de validación.
     */
    public static function registros(array $aFiltros = []) {
        try {
            $colObj = [];
            $tabla = self::getNombreTabla();
            $condiciones = self::sqlCondiciones($aFiltros);
            $sql = "SELECT * FROM $tabla WHERE 1 $condiciones";
            $aDatos = self::crudTodos($sql);
            foreach ($aDatos as $key) {
                $id = $key['id'];
                $colObj[$id] = new self($key);
            }
            return $colObj;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Determina si el usuario ó apodo es único.
     * @param string $usuario Usuario ó apodo.
     * @param Usuario|null $_Usuario
     * @return boolean true: El usuario es único,
     * false: Ya existe un registro con ese usuario.
     */
    private static function isUsuarioUnico($usuario, Usuario $_Usuario = null) {
        $tabla = self::getNombreTabla();
        $condiciones = '';
        if ($_Usuario instanceof Usuario) {
            $condiciones = "AND id <> {$_Usuario->getId()}";
        }
        $sql = "SELECT id FROM $tabla WHERE usuario = '$usuario' $condiciones";
        $id = self::crudUno($sql);
        return is_numeric($id) ? false : true;
    }

    /**
     * Determina si el correo electrónico es único.
     * @param string $correo Correo electrónico.
     * @param Usuario|null $_Usuario
     * @return boolean true: El correo es único,
     * false: Ya existe un registro con ese correo electrónico.
     */
    private static function isCorreoUnico($correo, Usuario $_Usuario = null) {
        $tabla = self::getNombreTabla();
        $condiciones = '';
        if ($_Usuario instanceof Usuario) {
            $condiciones = "AND correo <> {$_Usuario->getCorreo()}";
        }
        $sql = "SELECT id FROM $tabla WHERE correo = '$correo' $condiciones";
        $id = self::crudUno($sql);
        return is_numeric($id) ? false : true;
    }

    /**
     * Forma y retorna consulta en base a los filtros.
     * @param array $aFiltros Campos a filtrar.
     * @return array
     */
    private static function sqlCondiciones(array $aFiltros = []) {
        $condiciones = '';
        if (isset($aFiltros['id'])) {
            $id = trim($aFiltros['id']);
            if (!self::validaEntero($id)) {
                throw new \Exception('El id usuario no es válido.');
            }
            $condiciones .= " AND id = $id";
        }
        if (isset($aFiltros['usuario'])) {
            $usuario = trim($aFiltros['usuario']);
            if (!self::validaCadena($usuario)) {
                throw new \Exception('El usuario no es válido, no se permiten caracteres especiales.');
            }
            $condiciones .= " AND usuario = $usuario";
        }
        return $condiciones;
    }

    /**
     * Asigna valor a los propiedades.
     * @param array $aBD Campos de la base de datos.
     */
    private function __construct(array $aBD) {
        $this->asignaValorPropiedades($aBD);
    }

    /**
     * Asigna valor a los propiedades.
     * @param array $aBD Campos de la base de datos.
     * @return mixed
     */
    private function asignaValorPropiedades(array $aBD) {
        foreach ($aBD as $key => $value) {
            $this->{$key} = $value;
        }
    }
}