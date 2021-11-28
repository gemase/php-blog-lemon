<?php
namespace App\Models;

use App\Models\Perfil;
use App\Libraries\Session;

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
     * Instancia Perfil.
     * @var Perfil|null
     */
    private $_InsPerfil;
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
     * Instancia Perfil (Perfil del usuario).
     * @return Perfil
     */
    public function getInsPerfil() {
        if (is_null($this->_InsPerfil)) {
            $this->_InsPerfil = Perfil::load($this->getIdPerfil());
        }
        return $this->_InsPerfil;
    }
    /**
     * Nombre de la tabla de la clase.
     * @return string
     */
    public static function getNombreTabla() {
        return 'usuario';
    }

    /**
     * Inicia sesión de usuario.
     * @param string $usuarioCorreo Usuario ó correo electrónico.
     * @param string $clave Contraseña.
     * @return bool true: Se inicio la sesión correctamente, 
     * string: Mensaje de validación.
     */
    public static function iniciaSesion($usuarioCorreo, $clave) {
        try {
            if (!self::validaVacio($usuarioCorreo)) {
                throw new \Exception('El usuario ó correo electrónico es requerido.');
            } else {
                if (!self::validaCarEsp($usuarioCorreo)) {
                    throw new \Exception('El usuario ó correo electrónico no es válido, no se permiten caracteres especiales.');
                }
            }
            if (!self::validaVacio($clave)) {
                throw new \Exception('La contraseña es requerida.');
            }
            if (self::validaCorreo($usuarioCorreo)) {
                if (!self::validaLogMax($usuarioCorreo, 60)) {
                    throw new \Exception('El correo electrónico no es válido, el máximo de caracteres es "60".');
                }
                $colUsuarios = self::registros(['correo' => $usuarioCorreo]);
            } else {
                if (!self::validaLogMax($usuarioCorreo, 20)) {
                    throw new \Exception('El usuario no es válido, el máximo de caracteres es "20".');
                }
                $colUsuarios = self::registros(['usuario' => $usuarioCorreo]);
            }
            if (!is_array($colUsuarios)) throw new \Exception($colUsuarios);
            if (empty($colUsuarios)) {
                throw new \Exception('El usuario ó correo electrónico no pertenece a ninguna cuenta.');
            }
            $_Usuario = current($colUsuarios);
            $verificaClave = password_verify($clave, $_Usuario->getClave());
            if ($verificaClave !== true) {
                throw new \Exception('La contraseña no es correcta.');
            }
            if ($_Usuario->getEstatus() == self::E_INACTIVO) {
                throw new \Exception('Su cuenta esta inactiva.');
            }
            Session::add(Session::ID_USUARIO, $_Usuario->getId());
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Crea usuario.
     * @param Perfil $_Perfil Perfil a relacionar con el usuario.
     * @param array $aDatos Datos a registrar.
     * @return bool true: El usuario fue creado, false: Mensaje de 
     * validación.
     */
    public static function crea(Perfil $_Perfil, array $aDatos) {
        try {
            $aDatosEsperados = [
                'nombre', 'apellido', 'usuario', 'correo', 'clave', 'claveConfirmacion'
            ];
            foreach ($aDatos as $key => $value) {
                if (in_array($key, $aDatosEsperados)) {
                    $$key = trim($value);
                } else {
                    throw new \Exception("El dato \"$key\" no es aceptado para crear un usuario.");
                }
            }
            if (!isset($nombre) || !self::validaVacio($nombre)) {
                throw new \Exception('El nombre es requerido.');
            } else {
                if (!self::validaLogMax($nombre, 60)) {
                    throw new \Exception('El nombre no es válido, el máximo de caracteres es "40".');
                }
                if (!self::validaCarEsp($nombre)) {
                    throw new \Exception('El nombre no es válido, no se permiten caracteres especiales.');
                }
            }
            if (!isset($apellido) || !self::validaVacio($apellido)) {
                throw new \Exception('El apellido es requerido.');
            } else {
                if (!self::validaLogMax($apellido, 80)) {
                    throw new \Exception('El apellido no es válido, el máximo de caracteres es "80".');
                }
                if (!self::validaCarEsp($apellido)) {
                    throw new \Exception('El apellido no es válido, no se permiten caracteres especiales.');
                }
            }
            if (!isset($usuario) || !self::validaVacio($usuario)) {
                throw new \Exception('El usuario es requerido.');
            } else {
                if (!self::validaLogMax($usuario, 20)) {
                    throw new \Exception('El usuario no es válido, el máximo de caracteres es "20".');
                }
                if (!self::validaCarEsp($usuario)) {
                    throw new \Exception('El usuario no es válido, no se permiten caracteres especiales.');
                }
                if (!self::isUsuarioUnico($usuario)) {
                    throw new \Exception('El usuario ingresado ya fue ocupado.');
                }
            }
            if (!isset($correo) || !self::validaVacio($correo)) {
                throw new \Exception('El correo electrónico es requerido.');
            } else {
                if (!self::validaLogMax($correo, 60)) {
                    throw new \Exception('El correo electrónico no es válido, el máximo de caracteres es "60".');
                }
                if (!self::validaCarEsp($correo)) {
                    throw new \Exception('El correo electrónico no es válido, no se permiten caracteres especiales.');
                }
                if (!self::validaCorreo($correo)) {
                    throw new \Exception('El correo electrónico no es válido, usa el formato "nombre@ejemplo.com".');
                }
                if (!self::isCorreoUnico($correo)) {
                    throw new \Exception('El correo electrónico ingresado ya fue ocupado.');
                }
            }
            if (!isset($clave) || !self::validaVacio($clave)) {
                throw new \Exception('La contraseña es requerida.');
            } else {
                if (!self::validaLogMax($clave, 80)) {
                    throw new \Exception('La contraseña no es válida, el máximo de caracteres es "80".');
                }
            }
            if (!isset($claveConfirmacion) || !self::validaVacio($claveConfirmacion)) {
                throw new \Exception('La confirmación de la contraseña es requerida.');
            } else {
                if (!self::validaLogMax($claveConfirmacion, 80)) {
                    throw new \Exception('La confirmación de la contraseña no es válida, el máximo de caracteres es "80".');
                }
            }
            if ($clave != $claveConfirmacion) {
                throw new \Exception('Las contraseñas no coinciden.');
            }
            $aBD = [
                'id_perfil' => $_Perfil->getId(),
                'nombre' => $nombre,
                'apellido' => $apellido,
                'usuario' => strtolower($usuario),
                'correo' => $correo,
                'clave' => password_hash($clave, PASSWORD_DEFAULT),
                'estatus' => self::E_ACTIVO,
                'protegido' => self::NO_PROTEGIDO,
                'fecha_creo' => date('Y-m-d H:i:s')
            ];
            $result = self::crudCrea($aBD);
            if ($result !== true) throw new \Exception('Detalle en conexión de BD.');
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
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
        $usuario = strtolower($usuario);
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
            $condiciones = "AND id <> {$_Usuario->getId()}";
        }
        $sql = "SELECT id FROM $tabla WHERE correo = '$correo' $condiciones";
        $id = self::crudUno($sql);
        return is_numeric($id) ? false : true;
    }

    /**
     * Forma y retorna consulta en base a los filtros.
     * @param array $aFiltros Campos a filtrar.
     * @return string
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
            $usuario = strtolower($usuario);
            if (!self::validaCarEsp($usuario)) {
                throw new \Exception('El usuario no es válido, no se permiten caracteres especiales.');
            }
            $condiciones .= " AND usuario = '$usuario'";
        }
        if (isset($aFiltros['correo'])) {
            $correo = trim($aFiltros['correo']);
            if (!self::validaCarEsp($correo)) {
                throw new \Exception('El correo electrónico no es válido, no se permiten caracteres especiales.');
            }
            if (!self::validaCorreo($correo)) {
                throw new \Exception('El correo electrónico no es válido.');
            }
            $condiciones .= " AND correo = '$correo'";
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