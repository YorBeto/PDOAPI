<?php

namespace proyecto\Controller;

use proyecto\Models\Models;
use proyecto\Models\Table;
use proyecto\Auth;

class LoginController
{
    public function login() {
        // Obtén el cuerpo de la solicitud y decodifica el JSON
        $input = json_decode(file_get_contents('php://input'), true);
        
        $correo = $dataObject->correo;
        $contrasena = $dataObject->contrasena;


        $loguin=new Table();
        $loguearse=$loguin->query("SELECT
        PERSONA.NOMBRE AS nombre,               
        PERSONA.APELLIDO AS apellido,           
        PERSONA.CORREO AS correo,               
        USUARIOS.ID_USUARIO AS id_usuario,      
        CAST(AES_DECRYPT(USUARIOS.CONTRASEÑA, 'administrador') AS CHAR) AS contrasena
    FROM USUARIOS
    INNER JOIN PERSONA ON USUARIOS.ID_USUARIO = PERSONA.ID_USUARIO
    WHERE PERSONA.CORREO = '$correo'");

    Models::sendCorrect($loguearse);
    }
}
