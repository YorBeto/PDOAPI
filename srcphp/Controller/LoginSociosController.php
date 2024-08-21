<?php

namespace proyecto\Controller;

use proyecto\Models\User;
use proyecto\Models\Models;
use proyecto\Models\Table;

class LoginSociosController
{
    public function loginsocios()
    {
        // Obtén el cuerpo de la solicitud y decodifica el JSON
        $input = json_decode(file_get_contents('php://input'), true);
        
        $id_socio = $dataObject->id_socio;
        $contrasena = $dataObject->contrasena;


        $loguin=new Table();
        $loguearse=$loguin->query("SELECT 
        p.NOMBRE AS nombre,               
        p.APELLIDO AS apellido,           
        p.CORREO AS correo,               
        u.ID_USUARIO AS id_usuario,       
        socios.ID_SOCIO AS id_socio,      
        CAST(AES_DECRYPT(u.CONTRASEÑA, :clave_encriptacion) AS CHAR) AS contrasena
    FROM SOCIOS
    INNER JOIN CLIENTES ON SOCIOS.ID_CLIENTE = CLIENTES.ID_CLIENTES
    INNER JOIN PERSONA ON CLIENTES.ID_PERSONA = PERSONA.ID_PERSONA
    INNER JOIN USUARIOS ON PERSONA.ID_USUARIO = USUARIOS.ID_USUARIO
    WHERE SOCIOS.ID_SOCIO = '$id_socios'");

Models::sendCorrect([
    $token = Auth::generateToken([$loguearse['id_usuario']]),
    'usuario' => $loguearse,

]);


    }
}