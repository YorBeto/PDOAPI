<?php

namespace proyecto\Controller;

use proyecto\Models\Table;
use proyecto\Response\Success;
use proyecto\Response\Failure;
use Exception;

class ClasesController 
{
    public function inscripcionClases() {
        // Leer datos del cuerpo de la solicitud
        $JSONData = file_get_contents("php://input");
        $dataObject = json_decode($JSONData);

        // Verificar que las propiedades existen en el objeto
        if (!isset($dataObject->id_socio) || !isset($dataObject->id_clase)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
            return;
        }

        // Obtener los datos del objeto JSON
        $id_socio = $dataObject->id_socio;
        $id_clase = $dataObject->id_clase;

        // Preparar la llamada al procedimiento almacenado
        $query = "CALL InscribirSocioEnClase(
            '$id_socio', 
            '$id_clase'
        )";

        // Ejecutar la consulta y manejar errores
        try {
            // Ejecutar la consulta al procedimiento almacenado
            Table::query($query);

            // Enviar respuesta de éxito
            header('Content-Type: application/json');
            $r = new Success(['success' => true, 'message' => 'Registro exitoso']);
            $r->send();
        } catch (\Exception $e) {
            // Enviar respuesta de error
            header('Content-Type: application/json');
            $r = new Failure(500, 'Error en el registro: ' . $e->getMessage());
            $r->send();
        }
    }

    public function registrarAsistencia() {
        $JSONData = file_get_contents("php://input");
        $dataObject = json_decode($JSONData);

        if (!isset($dataObject->id_socio_clase) || !isset($dataObject->check_asistencia)) {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
            return;
        }

        $idSocioClase = $dataObject->id_socio_clase;
        $checkAsistencia = $dataObject->check_asistencia;

        try {
            $clasesModel = new \proyecto\Models\Clases();
            $clasesModel->registrarAsistencia($idSocioClase, $checkAsistencia);

            $r = new Success(['success' => true, 'message' => 'Asistencia registrada exitosamente']);
            return $r->send();
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error al registrar asistencia: ' . $e->getMessage()]);
        }
    } 

    public function editarClase() {
        $JSONData = file_get_contents("php://input");
        $dataObject = json_decode($JSONData);
    
        if (!isset($dataObject->id_clase) || !isset($dataObject->nombre) || !isset($dataObject->hora_clase)) {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
            return;
        }
    
        $idClase = $dataObject->id_clase;
        $nombre = $dataObject->nombre;
        $horaClase = $dataObject->hora_clase;
    
        try {
            $clasesModel = new \proyecto\Models\Clases();
            $clasesModel->editarClase($idClase, $nombre, $horaClase);
    
            $r = new Success(['success' => true, 'message' => 'Clase editada exitosamente']);
            return $r->send();
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error al editar la clase: ' . $e->getMessage()]);
        }
    }
}
