<?php
namespace App\Models;

use App\Libraries\Database;

abstract class Crud {
    /**
     * Nombre de tabla de la clase.
     * @return string
     */
    abstract protected static function getNombreTabla();

    /**
     * Insert
     * @param array $aBD Campos de la base de datos a editar.
     * @return bool|string
     */
    protected static function crudCrea(array $aBD) {
        try {
            $clase = get_called_class();
            $tabla = $clase::getNombreTabla();
            $llaves = array_keys($aBD);
            $campos = implode(', ', $llaves);
            $valores = implode(', :', $llaves);
            $_BD = Database::load();
            $sql = "INSERT INTO $tabla ($campos) VALUES (:$valores)";
            $_BD->query($sql);
            foreach ($aBD as $key => $value) {
                $_BD->bind(":$key", $value);
            }
            return $_BD->execute() ? true : false;
        } catch (\Exception $e) {
            error_log("Crud|crudCrea|$tabla = {$e->getMessage()}");
            return $e->getMessage();
        }
    }

    /**
     * Update
     * @param array $aBD Campos de la base de datos a editar.
     * @param array $aCB Condiciones para editar (where).
     * @return bool|string
     */
    protected static function crudEdita(array $aBD, array $aCB) {
        try {
            if (empty($aCB)) {
                throw new \Exception('Los campos por editar son requeridos.');
            }
            if (empty($aCB)) {
                throw new \Exception('Las condiciones son requeridas.');
            }
            $modelo = get_called_class();
            $tabla = $modelo::getNombreTabla();
            $llaves = '';
            foreach ($aBD as $key => $value) {
                $llaves .= "$key = :$key, ";
            }
            $llaves = trim($llaves, ', ');
            $condiciones = '';
            foreach ($aCB as $key => $value) {
                $condiciones .= "$key = :$key, ";
            }
            $condiciones = trim($condiciones, ', ');
            $_BD = Database::load();
            $sql = "UPDATE $tabla SET $llaves WHERE $condiciones";
            $_BD->query($sql);
            foreach ($aCB as $key => $value) {
                $_BD->bind(":$key", $value);
            }
            foreach ($aBD as $key => $value) {
                $_BD->bind(":$key", $value);
            }
            return $_BD->execute() ? true : false;
        } catch (\Exception $e) {
            error_log("Crud|crudEdita|$tabla = {$e->getMessage()}");
            return $e->getMessage();
        }
    }

    /**
     * Select - Información por registro (individual).
     * @param string $sql Consulta.
     * Ej: [id => 1, nombre => '', apellido => ''].
     * @return array|string
     */
    protected static function crudFila(string $sql) {
        try {
            $_BD = Database::load();
            $_BD->query($sql);
            $aFila = $_BD->single();
            return is_array($aFila) ? $aFila : [];
        } catch (\Exception $e) {
            error_log("Crud|crudFila = {$e->getMessage()}");
            return $e->getMessage();
        }
    }

    /**
     * Select - Todos los registros.
     * @param string $sql Consulta.
     * Ej: [0 => [id => 1, nombre => ''], 1 => [id => 2, nombre => '']].
     * @return array|string
     */
    protected static function crudTodos(string $sql) {
        try {
            $_BD = Database::load();
            $_BD->query($sql);
            return $_BD->resultSet();
        } catch (\Exception $e) {
            error_log("Crud|crudTodos = {$e->getMessage()}");
            return $e->getMessage();
        }
    }

    /**
     * Select - Un valor de registro.
     * Ej: id, count(*), nombre.
     * @param string $sql Consulta.
     * @return array|string
     */
    protected static function crudUno(string $sql) {
        try {
            $_BD = Database::load();
            $_BD->query($sql);
            return $_BD->rowNumber();
        } catch (\Exception $e) {
            error_log("Crud|crudUno = {$e->getMessage()}");
            return $e->getMessage();
        }
    }
}