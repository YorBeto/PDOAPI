<?php

namespace proyecto\Controller;

use proyecto\Models\Asistencias;
use proyecto\Response\Success;
use Exception;

class AsistenciasController 
{
    public function obtenerAlumnosPorClase($id_clase)
    {
        try {
            $asistenciasModel = new Asistencias();
            $result = $asistenciasModel->obtenerAlumnosPorClase($id_clase);
            return $result;
        } catch (Exception $e) {
            $failure = new Failure(500, $e->getMessage());
            return $failure->send();
        }
    }
}
