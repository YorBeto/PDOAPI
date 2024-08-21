<?php

namespace proyecto\Models;

use PDO;
use proyecto\Auth;
use proyecto\Response\Failure;
use proyecto\Response\Response;
use proyecto\Response\Success;
use function json_encode;

class User extends Models
{
    public $user = "";
    public $contrasena = "";
    public $nombre = "";
    public $edad = "";
    public $correo = "";
    public $apellido = "";

    public $id_usuario = "";

    /**
     * @var array
     */
    protected $filleable = [
        "nombre",
        "edad",
        "correo",
        "apellido",
        "contrasena",
        "user",
        "id_usuario",
    ];

    protected $table = "USUARIOS";
    protected $primaryKey = "ID_USUARIO";

   
    public static function auth($identificador, $contrasena)
    {
        $class = get_called_class();
        $c = new $class();
    

        $clave_encriptacion = 'administrador'; 
    
        try {
            if (filter_var($identificador, FILTER_VALIDATE_EMAIL)) {
            
                $stmt = self::$pdo->prepare("
                    SELECT
                        p.NOMBRE AS nombre,               -- Nombre del usuario
                        p.APELLIDO AS apellido,           -- Apellido del usuario
                        p.CORREO AS correo,               -- Correo electrónico del usuario
                        u.ID_USUARIO AS id_usuario,       -- ID del usuario
                        CAST(AES_DECRYPT(u.CONTRASEÑA, :clave_encriptacion) AS CHAR) AS contrasena
                    FROM {$c->table} u
                    INNER JOIN PERSONA p ON u.ID_USUARIO = p.ID_USUARIO
                    WHERE p.CORREO = :identificador
                ");
            } else {
             
                $stmt = self::$pdo->prepare("
                    SELECT 
                        p.NOMBRE AS nombre,               
                        p.APELLIDO AS apellido,           
                        p.CORREO AS correo,               
                        u.ID_USUARIO AS id_usuario,       
                        socios.ID_SOCIO AS id_socio,      
                        CAST(AES_DECRYPT(u.CONTRASEÑA, :clave_encriptacion) AS CHAR) AS contrasena
                    FROM socios
                    INNER JOIN clientes ON socios.ID_CLIENTE = clientes.ID_CLIENTES
                    INNER JOIN persona p ON clientes.ID_PERSONA = p.ID_PERSONA
                    INNER JOIN usuarios u ON p.ID_USUARIO = u.ID_USUARIO
                    WHERE socios.ID_SOCIO = :identificador
                ");
            }
    
            $stmt->bindParam(':identificador', $identificador);
            $stmt->bindParam(':clave_encriptacion', $clave_encriptacion);
            $stmt->execute();
    
            $resultado = $stmt->fetch(PDO::FETCH_OBJ);
    
            if ($resultado && $resultado->contrasena === $contrasena) {
   
                $tipoUsuario = $resultado->id_socio ? 'socio' : 'cliente';
    
                return [
                    'success' => true,
                    'usuario' => [
                        'nombre' => $resultado->nombre,
                        'apellido' => $resultado->apellido,
                        'correo' => $resultado->correo,
                        'id_usuario' => $resultado->id_usuario,
                        'id_socio' => $resultado->id_socio ?? null,
                        'tipoUsuario' => $tipoUsuario // Añadido
                    ],
                    '_token' => Auth::generateToken([$resultado->id_usuario ?? $resultado->id_socio])
                ];
            }
    
            return [
                'success' => false,
                'msg' => "Credenciales inválidas."
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'msg' => $e->getMessage()
            ];
        }
    }
    

    public function find_name($name)
    {
        $stmt = self::$pdo->prepare("SELECT * FROM $this->table WHERE nombre = :name");
        $stmt->bindParam(":name", $name);
        $stmt->execute();
        $resultados = $stmt->fetchAll(PDO::FETCH_OBJ);
        if ($resultados == null) {
            return json_encode([]);
        }
        return json_encode($resultados[0]);
    }

    public function reportecitas()
    {
        $JSONData = file_get_contents("php://input");
        $dataObject = json_decode($JSONData);

        $name = $dataObject->name;
        $d = Table::query("SELECT * FROM $this->table WHERE nombre = '".$name."'");
        $r = new Success($d);

        return $r->Send();
    }
}
