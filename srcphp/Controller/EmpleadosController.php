<?php

namespace proyecto\Controller;

use proyecto\Models\Personas;
use proyecto\Models\Table;
use proyecto\Response\Failure;
use proyecto\Response\Success;
use Exception;

class EmpleadosController {

    public function registroempleados() {
        // Leer datos del cuerpo de la solicitud
        $JSONData = file_get_contents("php://input");
        $dataObject = json_decode($JSONData);

        // Verificar que las propiedades existen en el objeto
        if (!isset($dataObject->nombre) || !isset($dataObject->apellidos) || 
            !isset($dataObject->fechaNacimiento) || !isset($dataObject->sexo) || 
            !isset($dataObject->correo) || !isset($dataObject->telefono) || 
            !isset($dataObject->contrasena) || !isset($dataObject->direccion) || 
            !isset($dataObject->curp) || !isset($dataObject->rfc) || 
            !isset($dataObject->numeroSeguro)) {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
            return;
        }

        // Obtener los datos del objeto JSON
        $nombre = $dataObject->nombre;
        $apellidos = $dataObject->apellidos;
        $fechaNacimiento = $dataObject->fechaNacimiento;
        $sexo = $dataObject->sexo;
        $correo = $dataObject->correo;
        $telefono = $dataObject->telefono;
        $contrasena = $dataObject->contrasena;
        $direccion = $dataObject->direccion;
        $curp = $dataObject->curp;
        $rfc = $dataObject->rfc;
        $numeroSeguro = $dataObject->numeroSeguro;

        // Llamar al procedimiento almacenado para registrar al empleado
        $query = "CALL RegistrarEmpleado(
            '$nombre', 
            '$apellidos', 
            '$fechaNacimiento', 
            '$sexo', 
            '$correo', 
            '$telefono', 
            '$contrasena',
            '$direccion',
            '$curp',
            '$rfc',
            '$numeroSeguro'
        )";

        // Ejecutar la consulta
        try {
            $resultados = Table::query($query);
            $r = new Success(['success' => true, 'message' => 'Registro exitoso']);
            return $r->send();
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error en el registro: ' . $e->getMessage()]);
            return;
        }

    }

    public function eliminarEmpleado() {
        // Leer datos del cuerpo de la solicitud
        $id = $_GET['id'] ?? '';

        if (empty($id)) {
            echo json_encode(['success' => false, 'message' => 'ID del empleado no proporcionado']);
            return;
        }

        // Ejecutar consulta para eliminar el empleado
        $query = "DELETE FROM EMPLEADOS WHERE ID_EMPLEADO = '$id'";

        try {
            Table::query($query);
            $r = new Success(['success' => true, 'message' => 'Empleado eliminado con éxito']);
            return $r->send();
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el empleado: ' . $e->getMessage()]);
            return;
        }
    }

    public function obtenerEmpleadoPorID() {
        $id = $_GET['id'] ?? '';

        if (empty($id)) {
            echo json_encode(['success' => false, 'message' => 'ID del empleado no proporcionado']);
            return;
        }

        $query = "CALL ObtenerEmpleadoPorID('$id')";

        try {
            $resultados = Table::query($query);
            echo json_encode(['success' => true, 'data' => $resultados]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error al obtener datos del empleado: ' . $e->getMessage()]);
        }
    }

    public function actualizarEmpleado() {
        $JSONData = file_get_contents("php://input");
        $dataObject = json_decode($JSONData);

        if (!isset($dataObject->idEmpleado) || !isset($dataObject->nombre) || !isset($dataObject->apellidos) ||
            !isset($dataObject->fechaNacimiento) || !isset($dataObject->sexo) || 
            !isset($dataObject->correo) || !isset($dataObject->telefono) ||
            !isset($dataObject->direccion) || !isset($dataObject->curp) || 
            !isset($dataObject->rfc) || !isset($dataObject->numeroSeguro)) {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
            return;
        }

        $idEmpleado = $dataObject->idEmpleado;
        $nombre = $dataObject->nombre;
        $apellidos = $dataObject->apellidos;
        $fechaNacimiento = $dataObject->fechaNacimiento;
        $sexo = $dataObject->sexo;
        $correo = $dataObject->correo;
        $telefono = $dataObject->telefono;
        $direccion = $dataObject->direccion;
        $curp = $dataObject->curp;
        $rfc = $dataObject->rfc;
        $numeroSeguro = $dataObject->numeroSeguro;

        $query = "CALL ActualizarEmpleado(
            '$idEmpleado',
            '$nombre',
            '$apellidos',
            '$fechaNacimiento',
            '$sexo',
            '$correo',
            '$telefono',
            '$direccion',
            '$curp',
            '$rfc',
            '$numeroSeguro'
        )";

        try {
            Table::query($query);
            echo json_encode(['success' => true, 'message' => 'Actualización exitosa']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error en la actualización: ' . $e->getMessage()]);
        }
    }
}