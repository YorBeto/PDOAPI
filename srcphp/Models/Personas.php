<?php


namespace proyecto\Models;
use proyecto\Models\Models;
use proyecto\Response\Success;




class Personas extends Models{

    
    protected $fillable = ["ID_PERSONA", "ID_USUARIO", "NOMBRE", "APELLIDO", "FECHA_NAC", "SEXO", "CORREO", "TELEFONO"];
    protected $table = "PERSONA";

    public function login() {
        // Leer el contenido de la solicitud JSON
        $JSONData = file_get_contents("php://input");
        $dataObject = json_decode($JSONData);

             
        $correo = $dataObject->correo;
        $contrasena = $dataObject->contrasena;

            $login=new Table();
            $loguearse=$login->query("SELECT PERSONA.CORREO,USUARIOS.CONTRASEÑA
            FROM USUARIOS INNER JOIN PERSONA ON USUARIOS.ID_USUARIO=PERSONA.ID_USUARIO
            WHERE '$correo'=Persona.CORREO 
            AND '$contrasena'=USUARIOS.CONTRASEÑA");

            $success=new Success($loguearse);
            return $success ->send();   
       
    }
}