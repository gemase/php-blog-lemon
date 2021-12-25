<?php
namespace App\Models;

class Perfil extends Crud {
    use Validador;
    
    //Estatus Inactivo.
    const E_INACTIVO = 0;
    
    //Estatus Activo.
    const E_ACTIVO = 1;
    
    //Estatus del perfil.
    const A_ESTATUS = [
        self::E_INACTIVO => 'Inactivo',
        self::E_ACTIVO => 'Activo'
    ];
    
    //Perfil no protegido (Puede ser editado).
    const NO_PROTEGIDO = 0;
    
    //Perfil protegido (No se permite su edición).
    const PROTEGIDO = 1;

    //Permiso Sin acceso.
    const P_SAC = 0;

    //Permiso Solo lectura.
    const P_LEC = 1;

    //Permiso Edición.
    const P_EDI = 2;

    //Permisos de catálogos.
    const A_PER = [
        self::P_SAC => 'Sin acceso',
        self::P_LEC => 'Solo lectura',
        self::P_EDI => 'Edición'
    ];

    /**
     * Identificador único del perfil.
     * @var integer
     */
    private $id;
    /**
     * Nombre del perfil.
     * @var string
     */
    private $nombre;
    /**
     * Estatus del perfil.
     * @var integer
     */
    private $estatus;
    /**
     * Permiso del catálogo de usuarios.
     * @var integer
     */
    private $c_usuarios;
    /**
     * Permiso para el catálogo de perfiles.
     * @var integer
     */
    private $c_perfiles;
    /**
     * Permiso para el catálogo de artículos.
     * @var integer
     */
    private $c_articulos;
    /**
     * Permiso para el catálogo de categorías.
     * @var integer
     */
    private $c_categorias;
    /**
     * Determina si el perfil puede ser editado.
     * @var integer
     */
    private $protegido;
    /**
     * Id usuario que creo el perfil.
     * @var integer
     */
    private $id_usuario_creo;
    /**
     * Fecha y hora de creación del perfil.
     * @var string
     */
    private $fecha_creo;
    /**
     * Identificador único del perfil.
     * @return integer
     */
    public function getId() {
        return $this->id;
    }
    /**
     * Nombre del perfil.
     * @return string
     */
    public function getNombre() {
        return $this->nombre;
    }
    /**
     * Estatus del perfil.
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
     * Permiso en el catálogo de usuarios.
     * @param boolean $muestraDes true: Retorna descripción,
     * false: Valor del campo.
     * @return integer
     */
    public function getPermisoUsuarios($muestraDes = false) {
        if ($muestraDes) {
            return self::A_PER[$this->c_usuarios];
        }
        return $this->c_usuarios;
    }
    /**
     * Permiso en el catálogo de perfiles.
     * @param boolean $muestraDes true: Retorna descripción,
     * false: Valor del campo.
     * @return integer
     */
    public function getPermisoPerfiles($muestraDes = false) {
        if ($muestraDes) {
            return self::A_PER[$this->c_perfiles];
        }
        return $this->c_perfiles;
    }
    /**
     * Permiso en el catálogo de artículos.
     * @param boolean $muestraDes true: Retorna descripción,
     * false: Valor del campo.
     * @return integer
     */
    public function getPermisoArticulos($muestraDes = false) {
        if ($muestraDes) {
            return self::A_PER[$this->c_articulos];
        }
        return $this->c_articulos;
    }
    /**
     * Permiso en el catálogo de categorías.
     * @param boolean $muestraDes true: Retorna descripción,
     * false: Valor del campo.
     * @return integer
     */
    public function getPermisoCategorias($muestraDes = false) {
        if ($muestraDes) {
            return self::A_PER[$this->c_categorias];
        }
        return $this->c_categorias;
    }
    /**
     * Determina si el perfil puede ser editado.
     * @return bool true: Perfil protegido,
     * false: Perfil no protegido.
     */
    public function esProtegido() {
        if ($this->protegido == self::PROTEGIDO) {
            return true;
        }
        return false;
    }
    /**
     * Id usuario que creo el perfil.
     * @return integer
     */
    public function getIdUsuarioCreo() {
        return $this->id_usuario_creo;
    }
    /**
     * Fecha y hora de creación del perfil.
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
        return 'perfil';
    }

    /**
     * Instancia Perfil.
     * @param integer $id Id perfil.
     * @return Perfil|string string: Mensaje de validación.
     */
    public static function load($id) {
        try {
            if (!self::validaVacio($id) || !self::validaEntero($id)) {
                throw new \Exception('El perfil no es válido.');
            }
            $colObj = self::registros(['id' => $id]);
            if (!is_array($colObj)) throw new \Exception($colObj);
            if (empty($colObj)) throw new \Exception('El perfil no existe.');
            return current($colObj);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public static function crea(Usuario $_Usuario, array $aDatos) {
        try {
            $aDatosEsperados = [
                'nombre', 'estatus', 'catalogoUsuarios', 'catalogoPerfiles', 
                'catalogoArticulos', 'catalogoCategorias'
            ];
            extract(self::extraeDatosEsperados($aDatosEsperados, $aDatos));
            if (!isset($nombre) || !self::validaVacio($nombre)) {
                throw new \Exception('El nombre es requerido.');
            } else {
                self::validaNombre($nombre);
            }
            if (!isset($estatus) || !self::validaVacio($estatus)) {
                throw new \Exception('El estatus es requerido.');
            } else {
                if (!array_key_exists($estatus, self::A_ESTATUS)) {
                    throw new \Exception('El estatus no es válido.');
                }
            }
            if (!isset($catalogoUsuarios) || !self::validaVacio($catalogoUsuarios)) {
                throw new \Exception('El permiso del catálogo de usuarios es requerido.');
            } else {
                if (!array_key_exists($catalogoUsuarios, self::A_PER)) {
                    throw new \Exception('El permiso del catálogo de usuarios no es válido.');
                }
            }
            if (!isset($catalogoPerfiles) || !self::validaVacio($catalogoPerfiles)) {
                throw new \Exception('El permiso del catálogo de perfiles es requerido.');
            } else {
                if (!array_key_exists($catalogoPerfiles, self::A_PER)) {
                    throw new \Exception('El permiso del catálogo de perfiles no es válido.');
                }
            }
            if (!isset($catalogoArticulos) || !self::validaVacio($catalogoArticulos)) {
                throw new \Exception('El permiso del catálogo de artículos es requerido.');
            } else {
                if (!array_key_exists($catalogoArticulos, self::A_PER)) {
                    throw new \Exception('El permiso del catálogo de artículos no es válido.');
                }
            }
            if (!isset($catalogoCategorias) || !self::validaVacio($catalogoCategorias)) {
                throw new \Exception('El permiso del catálogo de categorías es requerido.');
            } else {
                if (!array_key_exists($catalogoCategorias, self::A_PER)) {
                    throw new \Exception('El permiso del catálogo de categorías no es válido.');
                }
            }
            $aBD = [
                'nombre' => $nombre,
                'estatus' => $estatus,
                'c_usuarios' => $catalogoUsuarios,
                'c_perfiles' => $catalogoPerfiles,
                'c_articulos' => $catalogoArticulos,
                'c_categorias' => $catalogoCategorias,
                'protegido' => self::NO_PROTEGIDO,
                'id_usuario_creo' => $_Usuario->getId(),
                'fecha_creo' => date('Y-m-d H:i:s')
            ];
            $result = self::crudCrea($aBD);
            if ($result !== true) throw new \Exception('Detalle en conexión de BD.');
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function editaPerfil(Usuario $_Usuario, array $aDatos) {
        try {
            if ($this->protegido == self::PROTEGIDO) {
                throw new \Exception('La acción no pudo ser realizada. Perfil protegido por el sistema.');
            }
            $aBD = [];
            $aDatosEsperados = [
                'nombre', 'estatus', 'catalogoUsuarios', 'catalogoPerfiles', 
                'catalogoArticulos', 'catalogoCategorias'
            ];
            extract(self::extraeDatosEsperados($aDatosEsperados, $aDatos));
            if (isset($nombre)) {
                if ($this->nombre != $nombre) {
                    self::validaNombre($nombre, $this);
                    $aBD['nombre'] = $nombre;
                }
            }
            if (isset($estatus)) {
                if ($this->estatus != $estatus) {
                    if (!self::validaVacio($estatus)) {
                        throw new \Exception('El estatus es requerido.');
                    }
                    if (!array_key_exists($estatus, self::A_ESTATUS)) {
                        throw new \Exception('El estatus no es válido.');
                    }
                    $aBD['estatus'] = $estatus;
                }
            }
            if (isset($catalogoUsuarios)) {
                if ($this->c_usuarios != $catalogoUsuarios) {
                    if (!self::validaVacio($catalogoUsuarios)) {
                        throw new \Exception('El permiso del catálogo de usuarios es requerido.');
                    }
                    if (!array_key_exists($catalogoUsuarios, self::A_PER)) {
                        throw new \Exception('El permiso del catálogo de usuarios no es válido.');
                    }
                    $aBD['c_usuarios'] = $catalogoUsuarios;
                }
            }
            if (isset($catalogoPerfiles)) {
                if ($this->c_perfiles != $catalogoPerfiles) {
                    if (!self::validaVacio($catalogoPerfiles)) {
                        throw new \Exception('El permiso del catálogo de perfiles es requerido.');
                    }
                    if (!array_key_exists($catalogoPerfiles, self::A_PER)) {
                        throw new \Exception('El permiso del catálogo de perfiles no es válido.');
                    }
                    $aBD['c_perfiles'] = $catalogoPerfiles;
                }
            }
            if (isset($catalogoArticulos)) {
                if ($this->c_articulos != $catalogoArticulos) {
                    if (!self::validaVacio($catalogoArticulos)) {
                        throw new \Exception('El permiso del catálogo de artículos es requerido.');
                    }
                    if (!array_key_exists($catalogoArticulos, self::A_PER)) {
                        throw new \Exception('El permiso del catálogo de artículos no es válido.');
                    }
                    $aBD['c_articulos'] = $catalogoArticulos;
                }
            }
            if (isset($catalogoCategorias)) {
                if ($this->c_categorias != $catalogoCategorias) {
                    if (!self::validaVacio($catalogoCategorias)) {
                        throw new \Exception('El permiso del catálogo de categorías es requerido.');
                    }
                    if (!array_key_exists($catalogoCategorias, self::A_PER)) {
                        throw new \Exception('El permiso del catálogo de categorías no es válido.');
                    }
                    $aBD['c_categorias'] = $catalogoCategorias;
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
     * Retorna instancia de Perfil Autor.
     * @return Perfil
     * @throws Exception Mensaje de validación.
     */
    public static function getInsPerfilAutor() {
        $nombrePerfil = 'Autor';
        $tabla = self::getNombreTabla();
        $sql = "SELECT id FROM $tabla WHERE nombre = '$nombrePerfil'";
        $idPerfil = self::crudUno($sql);
        $_Perfil = self::load($idPerfil);
        if (!$_Perfil instanceof Perfil) {
            throw new \Exception('El perfil "Autor" no fue encontrado.');
        }
        return $_Perfil;
    }

    /**
     * Determina si el perfil tiene acceso a un determinado catálogo 
     * según el nombre del mismo y permiso.
     * @param string $catalogo Nombre del campo del catálogo a nivel 
     * base de datos.
     * @param string $permiso Permiso a verificar.
     * @return bool|string bool|true: Tiene permiso, 
     * string: No tiene permiso (Mensaje de validación).
     */
    public function tienePermiso(string $catalogo, string $permiso) {
        try {
            if (!array_key_exists($permiso, self::A_PER)) {
                throw new \Exception('El permiso no es válido. 
                No fue encontrado entre los permisos del sistema.');
            }
            $nombrePermiso = self::A_PER[$permiso];
            switch ($catalogo) {
                case 'c_usuarios':
                    $permisoCatalogo = $this->getPermisoUsuarios();
                    $nombreCatalogo = 'Catálogo de usuarios';
                    break;
                case 'c_perfiles':
                    $permisoCatalogo = $this->getPermisoPerfiles();
                    $nombreCatalogo = 'Catálogo de perfiles';
                    break;
                case 'c_articulos':
                    $permisoCatalogo = $this->getPermisoArticulos();
                    $nombreCatalogo = 'Catálogo de artículos';
                    break;
                case 'c_categorias':
                    $permisoCatalogo = $this->getPermisoCategorias();
                    $nombreCatalogo = 'Catálogo de categorías';
                    break;
                default:
                    throw new \Exception("El catálogo \"$catalogo\" no es válido. 
                    No fue encontrado entre los catálogos del sistema.");
                    break;
            }
            if ($this->getEstatus() == self::E_INACTIVO) {
                throw new \Exception('La acción no pudo ser realizada, su perfil se encuentra inactivo.');
            }
            if ($permisoCatalogo >= $permiso) {
                return true;
            }
            throw new \Exception("La acción no pudo ser realizada, 
            requiere tener permiso de \"$nombrePermiso\" en el \"$nombreCatalogo\".");
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Retorna la cantidad de perfiles encontrados.
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
     * Retorna colección de instancias Perfil.
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

    private static function validaNombre($nombre, Perfil $_Perfil = null) {
        if (!self::validaVacio($nombre)) {
            throw new \Exception('El nombre es requerido.');
        }
        if (!self::validaLogMax($nombre, 60)) {
            throw new \Exception('El nombre no es válido, el máximo de caracteres es "40".');
        }
        if (!self::validaCarEsp($nombre)) {
            throw new \Exception('El nombre no es válido, no se permiten caracteres especiales.');
        }
        if (!self::isNombreUnico($nombre, $_Perfil)) {
            throw new \Exception('El nombre ingresado ya fue ocupado.');
        }
    }

    /**
     * Determina si el nombre de perfil es único.
     * @param string $nombre Nombre de perfil.
     * @param Perfil|null $_Perfil
     * @return boolean true: El nombre es único,
     * false: Ya existe un registro con ese nombre.
     */
    private static function isNombreUnico($nombre, Perfil $_Perfil = null) {
        $tabla = self::getNombreTabla();
        $condiciones = '';
        if ($_Perfil instanceof Perfil) {
            $condiciones = "AND id <> {$_Perfil->getId()}";
        }
        $sql = "SELECT id FROM $tabla WHERE nombre = '$nombre' $condiciones";
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
                throw new \Exception('El id perfil no es válido.');
            }
            $condiciones .= " AND id = $id";
        }
        if (isset($aFiltros['estatus'])) {
            $estatus = trim($aFiltros['estatus']);
            if (!array_key_exists($estatus, self::A_ESTATUS)) {
                throw new \Exception('El estatus no es válido.');
            }
            $condiciones .= " AND estatus = $estatus";
        }
        if (isset($aFiltros['buscar'])) {
            $buscar = trim($aFiltros['buscar']);
            if (!self::validaCarEsp($buscar)) {
                throw new \Exception('El campo buscar no es válido, no se permiten caracteres especiales.');
            }
            $condiciones .= " AND nombre LIKE '%$buscar%'";
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