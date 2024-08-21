<?php

namespace proyecto\Controller;

namespace proyecto\Controller;

use proyecto\Models\Contraseña;
use proyecto\Response\Success;
use proyecto\Models\Table;

class ContraseñaController {

    public function almacenarContrasena($contrasena) {
        // Cifrar la contraseña
        $contrasena_cifrada = password_hash($contrasena, PASSWORD_BCRYPT);

        // Guardar la contraseña cifrada en la base de datos
        $query = "INSERT INTO contraseñas (contrasena) VALUES ('$contrasena_cifrada')";
        $resultados = Table::query($query);

        return $resultados;
    }
}