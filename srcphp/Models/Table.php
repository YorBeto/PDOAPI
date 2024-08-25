<?php

namespace proyecto\Models;

use PDO;
use proyecto\Conexion;
use Dotenv\Dotenv;

class Table
{
    public static $pdo = null;
    public function __construct()
    {

    }
    static  function getDataconexion(){




}

static function query($query)
{
    $cc = new  Conexion('arsenal_gym','localhost','root','');
    self::$pdo = $cc->getPDO();
    $stmt = self::$pdo->query($query);
    $resultados = $stmt->fetchAll(PDO::FETCH_OBJ);
    return $resultados;
}
static function beginTransaction()
    {
        if (self::$pdo === null) {
            $cc = new Conexion('arsenal_gym', 'localhost', 'root', '');
            self::$pdo = $cc->getPDO();
        }
        self::$pdo->beginTransaction();
    }

    static function commit()
    {
        if (self::$pdo !== null) {
            self::$pdo->commit();
        }
    }

    static function rollBack()
    {
        if (self::$pdo !== null) {
            self::$pdo->rollBack();
        }
    }


}
