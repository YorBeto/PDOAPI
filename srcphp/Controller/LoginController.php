<?php

namespace proyecto\Controller;

use proyecto\Models\User;
use proyecto\Response\Failure;
use proyecto\Response\Success;
use Exception;

class LoginController
{
    /**
     * Método para manejar la solicitud de inicio de sesión.
     */
    public function login()
    {
        try {
            // Obtén el cuerpo de la solicitud y decodifica el JSON
            $input = json_decode(file_get_contents('php://input'), true);

            // Verifica si los datos fueron decodificados correctamente
            if (json_last_error() !== JSON_ERROR_NONE) {
                return (new Failure(["msg" => "Error al procesar datos JSON."], 400))->Send();
            }

            // Obtén los datos del JSON
            $identificador = $input['identificador'] ?? null; // Puede ser correo o ID de socio
            $contrasena = $input['contrasena'] ?? null;

            // Verifica los datos recibidos
            if (!$identificador || !$contrasena) {
                return (new Failure(["msg" => "Datos incompletos."], 400))->Send();
            }

            // Utiliza el método auth para autenticar clientes o socios
            $resultado = User::auth($identificador, $contrasena);

            if ($resultado['success']) {
                return (new Success([
                    "usuario" => $resultado['usuario'],
                    "_token" => $resultado['_token']
                ]))->Send();
            } else {
                return (new Failure(["msg" => $resultado['msg']], 401))->Send();
            }
        } catch (Exception $e) {
            return (new Failure(["msg" => "Ocurrió un error en el servidor. Inténtalo de nuevo más tarde."], 500))->Send();
        }
    }
}
