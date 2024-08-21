<?php
namespace proyecto\Controller;

use proyecto\Auth;
use proyecto\Models\Table;
use proyecto\Models\Usuario;
use proyecto\Response\Failure;
use proyecto\Response\Success;
use function json_encode;

class UsuariosController{
    public function mostrarUsuarios(){
        $res=Table::query('select * from usuarios;');
        $res=new Success($res);
        $res->Send();
    }
    public function Register(){
        try {
            $JSONData = file_get_contents('php://input');
            $dataObject = json_decode($JSONData);
            $user = new Usuario();
            $user->nombre=$dataObject->nombre;
            $user->apellido_paterno=$dataObject->apellido_paterno;
            $user->apellido_materno=$dataObject->apellido_materno;
            $user->user=$dataObject->user;
            $user->contrasena=$dataObject->contrasena;
            $user->telefono=$dataObject->telefono;
            $user->Save();

            $r = new Success($user);
            return $r->send();
        } catch (error) {
            console.error('error en nose donde');
        }
    }
    public function auth()
    {
        try {
            $JSONData = file_get_contents("php://input");
            $dataObject = json_decode($JSONData);
            if (!property_exists($dataObject, "user") || !property_exists($dataObject, "contrasena")) {
                throw new \Exception("Faltan datos");
            }
            return Usuario::auth($dataObject->user, $dataObject->contrasena);
            
        } catch (\Exception $e) {
            $r = new Failure(401, $e->getMessage());
            return $r->Send();
        }
    }
}