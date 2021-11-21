<?php
namespace App\Libraries;

class Database {
    /**
     * Instancia de Database.
     * @var Database|null
     */
    private static $_DB;
    /**
     * Ubicación de alojamiento de base de datos.
     * @var string
     */
    private $host = DB_HOST;
    /**
     * Usuario de la base de datos.
     * @var string
     */
    private $user = DB_USER;
    /**
     * Clave o contraseña de la base de datos.
     * @var string
     */
    private $pass = DB_PASS;
    /**
     * Nombre de la base de datos.
     * @var string
     */
    private $dbname = DB_NAME;
    /**
     * Instancia de PDO.
     * dbh (Database handle): Manejador de base de datos.
     * @var PDO
     */
    private $dbh;
    /**
     * Sentencia.
     * @var mixed
     */
    private $stmt;
    
    private function __construct() {
        try {
            //Fuente de datos.
            $dsn = "mysql:host={$this->host};dbname={$this->dbname}";
            $options = [
                \PDO::ATTR_PERSISTENT => true,
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
            ];
            $this->dbh = new \PDO($dsn, $this->user, $this->pass, $options);
        } catch(\PDOException $e) {
            error_log("Database|PDOException = {$e->getMessage()}");
        }
    }

    /**
     * Retorna instancia Database.
     * @return Database
     */
    public static function load() {
        if (is_null(self::$_DB)) {
            self::$_DB = new self();
        }
        return self::$_DB;
    }

    /**
	 * Consulta.
	 * @param string
	 */
	public function query($sql) {
        $this->stmt = $this->dbh->prepare($sql);
    }

    /**
     * Pasar valores a tratar.
     * @param string $param Nombre del campo.
     * @param string $value Valor del campo.
     * @param mixed $type Tipo de dato.
     * @return mixed
     */
    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = \PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = \PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = \PDO::PARAM_NULL;
                    break;
                default:
                    $type = \PDO::PARAM_STR;
			}
		}
		$this->stmt->bindValue($param, $value, $type);
	}

	//Ejecuta sentencia.
	public function execute() {
        return $this->stmt->execute();
	}

	//Arreglo.
	public function resultSet() {
		$this->execute();
		return $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	//Individual.
	public function single() {
		$this->execute();
		return $this->stmt->fetch(\PDO::FETCH_ASSOC);
	}

	//Número de filas afectadas.
	public function rowCount() {
		return $this->stmt->rowCount();
	}

	//Número de filas en consultas de información.
	public function rowNumber() {
		$this->execute();
		return $this->stmt->fetchColumn();
	}
}