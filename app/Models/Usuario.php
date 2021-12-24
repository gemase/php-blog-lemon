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

    //Género masculino.
    const G_MASCULINO = 1;

    //Género femenino.
    const G_FEMENINO = 2;

    //Género otro.
    const G_OTRO = 3;

    //Generos del usuario.
    const A_GENEROS = [
        self::G_MASCULINO => 'Masculino',
        self::G_FEMENINO => 'Femenino',
        self::G_OTRO => 'Otro'
    ];

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
     * País del usuario.
     * @var string|null
     */
    private $pais;
    /**
     * Ciudad del usuario.
     * @var string|null
     */
    private $ciudad;
    /**
     * Género del usuario.
     * @var integer|null
     */
    private $genero;
    /**
     * Fecha de nacimiento del usuario.
     * @var string|null
     */
    private $fecha_nacimiento;
    /**
     * Biografía del usuario.
     * @var string|null
     */
    private $biografia;
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
     * País del usuario.
     * @return string|null
     */
    public function getPais() {
        return $this->pais;
    }
    /**
     * Ciudad del usuario.
     * @return string|null
     */
    public function getCiudad() {
        return $this->ciudad;
    }
    /**
     * Género del usuario.
     * @param boolean $muestraDes true: Retorna descripción,
     * false: Valor del campo.
     * @return string|integer|null
     */
    public function getGenero($muestraDes = false) {
        if ($muestraDes && !is_null($this->genero)) {
            return self::A_GENEROS[$this->genero];
        }
        return $this->genero;
    }
    /**
     * Fecha de nacimiento del usuario.
     * @param boolean $objeto true: Instancia DateTime,
     * false: Valor del campo.
     * @return string|DateTime|null
     */
    public function getFechaNacimiento($objeto = false) {
        if ($objeto && !is_null($this->fecha_nacimiento)) {
            return new \DateTime($this->fecha_nacimiento);
        }
        return $this->fecha_nacimiento;
    }
    /**
     * Biografía del usuario.
     * @return string|null
     */
    public function getBiografia() {
        return $this->biografia;
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
     * Instancia Usuario en base al usuario ó apodo.
     * @param string $usuario Nombre de usuario ó apodo.
     * @return Usuario|string string: Mensaje de validación
     */
    public static function getInsByUsuario($usuario) {
        try {
            $usuario = trim($usuario);
            if (!self::validaVacio($usuario)) {
                throw new \Exception('El usuario es requerido.');
            }
            if (!self::validaLogMax($usuario, 20)) {
                throw new \Exception('El usuario no es válido, el máximo de caracteres es "20".');
            }
            if (!self::validaCarEsp($usuario)) {
                throw new \Exception('El usuario no es válido, no se permiten caracteres especiales.');
            }
            $colUsuarios = self::registros(['usuario' => $usuario]);
            if (!is_array($colUsuarios)) throw new \Exception($colUsuarios);
            if (empty($colUsuarios)) {
                throw new \Exception('El usuario no existe.');
            }
            return current($colUsuarios);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
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
     * Edita información general del usuario.
     * @param Usuario $_Usuario Usuario en sesión.
     * @param array $aDatos Datos a editar.
     * @return bool|string true: El usuario fue editado, string: Mensaje de 
     * validación.
     */
    public function editaInformacionGeneral(Usuario $_Usuario, array $aDatos) {
        try {
            $aBD = [];
            $aDatosEsperados = [
                'nombre', 'apellido', 'pais', 'ciudad', 'genero', 'fechaNacimiento', 
                'biografia'
            ];
            extract(self::extraeDatosEsperados($aDatosEsperados, $aDatos));
            if (isset($nombre) && $this->getNombre() != $nombre) {
                if (!self::validaVacio($nombre)) {
                    throw new \Exception('El nombre es requerido.');
                }
                if (!self::validaLogMax($nombre, 60)) {
                    throw new \Exception('El nombre no es válido, el máximo de caracteres es "40".');
                }
                if (!self::validaCarEsp($nombre)) {
                    throw new \Exception('El nombre no es válido, no se permiten caracteres especiales.');
                }
                $aBD['nombre'] = $nombre;
            }
            if (isset($apellido) && $this->getApellido() != $apellido) {
                if (!self::validaVacio($apellido)) {
                    throw new \Exception('El apellido es requerido.');
                }
                if (!self::validaLogMax($apellido, 80)) {
                    throw new \Exception('El apellido no es válido, el máximo de caracteres es "80".');
                }
                if (!self::validaCarEsp($apellido)) {
                    throw new \Exception('El apellido no es válido, no se permiten caracteres especiales.');
                }
                $aBD['apellido'] = $apellido;
            }
            if (isset($pais) && $this->getPais() != $pais) {
                if (self::validaVacio($pais)) {
                    if (!self::validaLogMax($pais, 40)) {
                        throw new \Exception('El país no es válido, el máximo de caracteres es "40".');
                    }
                    if (!self::validaCarEsp($pais)) {
                        throw new \Exception('El país no es válido, no se permiten caracteres especiales.');
                    }
                    $aBD['pais'] = $pais;
                } else {
                    $aBD['pais'] = null;
                }
            }
            if (isset($ciudad) && $this->getCiudad() != $ciudad) {
                if (self::validaVacio($ciudad)) {
                    if (!self::validaLogMax($ciudad, 40)) {
                        throw new \Exception('El ciudad no es válido, el máximo de caracteres es "40".');
                    }
                    if (!self::validaCarEsp($ciudad)) {
                        throw new \Exception('El ciudad no es válido, no se permiten caracteres especiales.');
                    }
                    $aBD['ciudad'] = $ciudad;
                } else {
                    $aBD['ciudad'] = null;
                }
            }
            if (isset($genero) && $this->getGenero() != $genero) {
                if (self::validaVacio($genero)) {
                    if (!array_key_exists($genero, self::A_GENEROS)) {
                        throw new \Exception('El genero no es válido.');
                    }
                    $aBD['genero'] = $genero;
                } else {
                    $aBD['genero'] = null;
                }
            }
            if (isset($fechaNacimiento) && $this->getFechaNacimiento() != $fechaNacimiento) {
                if (self::validaVacio($fechaNacimiento)) {
                    if (!self::validaFecha($fechaNacimiento)) {
                        throw new \Exception('La fecha de nacimiento no es válida.');
                    }
                    $aBD['fecha_nacimiento'] = $fechaNacimiento;
                } else {
                    $aBD['fecha_nacimiento'] = null;
                }
            }
            if (isset($biografia) && $this->getBiografia() != $biografia) {
                if (self::validaVacio($biografia)) {
                    if (!self::validaCarEsp($biografia)) {
                        throw new \Exception('El biografía no es válida, no se permiten caracteres especiales.');
                    }
                    $aBD['biografia'] = $biografia;
                } else {
                    $aBD['biografia'] = null;
                }
            }
            if (!empty($aBD)) {
                $result = self::crudEdita($aBD, ['id' => $this->getId()]);
                if ($result !== true) throw new \Exception('Detalle en conexión de BD.');
            }
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Edita información de cuenta del usuario.
     * @param Usuario $_Usuario Usuario en sesión.
     * @param array $aDatos Datos a editar.
     * @param Perfil|null $_Perfil Perfil.
     * @return bool|string true: El usuario fue editado, string: Mensaje de 
     * validación.
     */
    public function editaInformacionCuenta($_Usuario, array $aDatos, Perfil $_Perfil = null) {
        try {
            $aBD = [];
            $aDatosEsperados = ['usuario', 'correo', 'estatus'];
            extract(self::extraeDatosEsperados($aDatosEsperados, $aDatos));
            if (isset($usuario) && $this->getUsuario() != $usuario) {
                if (!self::validaVacio($usuario)) {
                    throw new \Exception('El usuario es requerido.');
                }
                if (!self::validaLogMax($usuario, 20)) {
                    throw new \Exception('El usuario no es válido, el máximo de caracteres es "20".');
                }
                if (!self::validaCarEsp($usuario)) {
                    throw new \Exception('El usuario no es válido, no se permiten caracteres especiales.');
                }
                if (!self::isUsuarioUnico($usuario, $this)) {
                    throw new \Exception('El usuario ingresado ya fue ocupado.');
                }
                $aBD['usuario'] = $usuario;
            }
            if (isset($correo) && $this->getCorreo() != $correo) {
                if (!self::validaVacio($correo)) {
                    throw new \Exception('El correo electrónico es requerido.');
                }
                if (!self::validaLogMax($correo, 60)) {
                    throw new \Exception('El correo electrónico no es válido, el máximo de caracteres es "60".');
                }
                if (!self::validaCarEsp($correo)) {
                    throw new \Exception('El correo electrónico no es válido, no se permiten caracteres especiales.');
                }
                if (!self::validaCorreo($correo)) {
                    throw new \Exception('El correo electrónico no es válido, usa el formato "nombre@ejemplo.com".');
                }
                if (!self::isCorreoUnico($correo, $this)) {
                    throw new \Exception('El correo electrónico ingresado ya fue ocupado.');
                }
                $aBD['correo'] = $correo;
            }
            if ($_Perfil instanceof Perfil) {
                if ($this->getIdPerfil() != $_Perfil->getId()) {
                    $aBD['id_perfil'] = $_Perfil->getId();
                }
            }
            if (isset($estatus) && $this->getEstatus() != $estatus) {
                if (!self::validaVacio($estatus)) {
                    throw new \Exception('El estatus es requerido.');
                }
                if (!array_key_exists($estatus, self::A_ESTATUS)) {
                    throw new \Exception('El estatus no es válido.');
                }
                $aBD['estatus'] = $estatus;
            }
            if (!empty($aBD)) {
                $result = self::crudEdita($aBD, ['id' => $this->getId()]);
                if ($result !== true) throw new \Exception('Detalle en conexión de BD.');
            }
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Edita información de contraseña del usuario.
     * @param Usuario $_Usuario Usuario en sesión.
     * @param array $aDatos Datos a editar.
     * @param boolean $validarClavaActual true: Se toma en cuenta la clave
     * actual y se valida, false: Se omite validaciones de clave actual.
     * @return bool|string true: El usuario fue editado, string: Mensaje de 
     * validación.
     */
    public function editaInformacionClave(Usuario $_Usuario, array $aDatos, $validarClavaActual = true) {
        try {
            $aBD = [];
            $aDatosEsperados = ['claveActual', 'claveNueva', 'claveConfirmacion'];
            extract(self::extraeDatosEsperados($aDatosEsperados, $aDatos));
            if ($validarClavaActual) { //Tomamos en cuenta la clave actual y la validamos.
                if (!isset($claveActual) || !self::validaVacio($claveActual)) {
                    throw new \Exception('La contraseña actual es requerida.');
                } else {
                    if (!self::validaLogMax($claveActual, 80)) {
                        throw new \Exception('La contraseña actual no es válida, el máximo de caracteres es "80".');
                    }
                }
                $verificaClave = password_verify($claveActual, $this->getClave());
                if ($verificaClave !== true) {
                    throw new \Exception('La contraseña actual no es correcta.');
                }
            }
            if (!isset($claveNueva) || !self::validaVacio($claveNueva)) {
                throw new \Exception('La nueva contraseña es requerida.');
            } else {
                if (!self::validaLogMax($claveNueva, 80)) {
                    throw new \Exception('La nueva contraseña no es válida, el máximo de caracteres es "80".');
                }
            }
            if (!isset($claveConfirmacion) || !self::validaVacio($claveConfirmacion)) {
                throw new \Exception('La confirmación de la nueva contraseña es requerida.');
            } else {
                if (!self::validaLogMax($claveConfirmacion, 80)) {
                    throw new \Exception('La confirmación de la nueva contraseña no es válida, el máximo de caracteres es "80".');
                }
            }
            if ($claveNueva != $claveConfirmacion) {
                throw new \Exception('La nueva contraseña no coincide con la confirmación.');
            }
            $aBD = ['clave' => password_hash($claveNueva, PASSWORD_DEFAULT)];
            if (!empty($aBD)) {
                $result = self::crudEdita($aBD, ['id' => $this->getId()]);
                if ($result !== true) throw new \Exception('Detalle en conexión de BD.');
            }
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Crea usuario.
     * @param Perfil $_Perfil Perfil a relacionar con el usuario.
     * @param array $aDatos Datos a registrar.
     * @return bool|string true: El usuario fue creado, string: Mensaje de 
     * validación.
     */
    public static function crea(Perfil $_Perfil, array $aDatos) {
        try {
            $aDatosEsperados = [
                'nombre', 'apellido', 'usuario', 'correo', 'clave', 'claveConfirmacion',
                'estatus'
            ];
            extract(self::extraeDatosEsperados($aDatosEsperados, $aDatos));
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
            if (isset($estatus)) {
                if (!self::validaVacio($estatus)) {
                    throw new \Exception('El estatus es requerido.');
                }
                if (!array_key_exists($estatus, self::A_ESTATUS)) {
                    throw new \Exception('El estatus no es válido.');
                }
            } else { //Si no llega un estatus, asignamos el estatus predeterminado.
                $estatus = self::E_ACTIVO; //Predeterminado: estatus activo.
            }
            $aBD = [
                'id_perfil' => $_Perfil->getId(),
                'nombre' => $nombre,
                'apellido' => $apellido,
                'usuario' => strtolower($usuario),
                'correo' => $correo,
                'clave' => password_hash($clave, PASSWORD_DEFAULT),
                'estatus' => $estatus,
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
     * Retorna la cantidad de usuarios encontrados.
     * @param array $aFiltros Campos a filtrar.
     * @return integer|string
     */
    public static function cantidadRegistros(array $aFiltros = []) {
        try {
            $tabla = self::getNombreTabla();
            $condiciones = self::sqlCondiciones($aFiltros);
            $sql = "SELECT COUNT(*) FROM $tabla WHERE 1 $condiciones";
            return self::crudUno($sql);
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
            if (!is_array($aDatos)) throw new \Exception($aDatos);
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
     * @throws Exception Mensaje de validación.
     * @return string
     */
    private static function sqlCondiciones(array $aFiltros = []) {
        $condiciones = $condicionLimite = '';
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
        if (isset($aFiltros['estatus'])) {
            $estatus = trim($aFiltros['estatus']);
            if (!array_key_exists($estatus, self::A_ESTATUS)) {
                throw new \Exception('El estatus no es válido.');
            }
            $condiciones .= " AND estatus = $estatus";
        }
        if (isset($aFiltros['idPerfil'])) {
            $idPerfil = trim($aFiltros['idPerfil']);
            if (!self::validaEntero($idPerfil)) {
                throw new \Exception('El id perfil no es válido.');
            }
            $condiciones .= " AND id_perfil = $idPerfil";
        }
        if (isset($aFiltros['fechaInicial'])) {
            $fechaInicial = trim($aFiltros['fechaInicial']);
            if (!self::validaFecha($fechaInicial)) {
                throw new \Exception('La fecha inicial no es válida.');
            }
            $condiciones .= " AND fecha_creo >= '$fechaInicial 00:00:00'";
        }
        if (isset($aFiltros['fechaFinal'])) {
            $fechaFinal = trim($aFiltros['fechaFinal']);
            if (!self::validaFecha($fechaFinal)) {
                throw new \Exception('La fecha final no es válida.');
            }
            $condiciones .= " AND fecha_creo <= '$fechaFinal 23:59:59'";
        }
        if (isset($aFiltros['buscar'])) {
            $buscar = trim($aFiltros['buscar']);
            if (!self::validaCarEsp($buscar)) {
                throw new \Exception('El campo buscar no es válido, no se permiten caracteres especiales.');
            }
            $condiciones .= " AND (nombre LIKE '%$buscar%' OR apellido LIKE '%$buscar%' OR usuario LIKE '%$buscar%' OR correo LIKE '%$buscar%')";
        }
        if (isset($aFiltros['limite'])) {
            $limite = trim($aFiltros['limite']);
            $condicionLimite .= " LIMIT $limite";
        }
        return "$condiciones $condicionLimite";
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