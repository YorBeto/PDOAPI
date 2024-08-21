<?php

namespace proyecto\Controller;

use PDO;
use proyecto\Models\Models;
use proyecto\Models\Table;
use proyecto\Models\Servicio_cita;
use proyecto\Response\Failure;
use proyecto\Response\Success;
use proyecto\Conexion;

class ServiciosCitasController{
    private $conexion;
    public function __construct() {
        $this->conexion = new  Conexion("estetica","localhost","root","04063017");
            
    }
    public function citas(){
        $res=Table::query("select * from servicio_cita");
        $res=new Success($res);
        $res->Send();
    }
    public function servicios_Nombre(){
        $res=Table::query("        
        select (select nombre from servicios where id = sc.id_servicio) as nombre, id_cita from servicio_cita as sc;");
        $res=new Success($res);
        $res->Send();
    }
    public function mostrar(){
        $res=Table::query("select servCita.id as id,servCita.id_cita as idCita,servCita.id_servicio as idServicio,servCita.precio as precio,
        servCita.duracion_min as duracion,servCita.fecha_hora as fechaServicio,servCita.tipo as tipo,servicio.categoria 
        as catalogo from servicio_cita as servCita
        inner join registro_citas as cita on cita.id=servCita.id_cita inner join
        servicios as servicio on servicio.id = servCita.id_servicio where cita.estado = 'confirmado';");
        $res=new Success($res);
        $res->Send();
    }
    public function mostrar_bloqueos(){
        $res=Table::query("select servCita.id as id,servCita.id_cita as idCita,servCita.id_servicio as idServicio,
        servCita.duracion_min as duracion,servCita.fecha_hora as fechaServicio,servCita.tipo as tipo from servicio_cita as servCita 
        where servCita.tipo=1;");
        $res=new Success($res);
        $res->Send();
    }
    public function crear_cita(){
        try{
            $JSONData = file_get_contents('php://input');
            $dataObject = json_decode($JSONData);
            $sc = new servicio_cita();
            $sc->id_servicio=$dataObject->id_servicio;
            $sc->precio=$dataObject->precio;
            $sc->duracion_min=$dataObject->duracion_min;
            $sc->fecha_hora=$dataObject->fecha_hora;
            $sc->tipo=$dataObject->tipo;
            $sc->save();
            
            $r = new Success($sc);
            return $r->send();
        }catch(\Exception $e){
            $sc = new failure(400, "Error al crear cita verifica tus datos. info error:E-081927");
            return $sc ->Send();
        }
    }
     // funcion para borrar citas por la id//
     public function borrar_cita(){
        try{
            $JSONData = file_get_contents('php://input');
            $dataObject = json_decode($JSONData);
            $sc = new servicio_cita();
            $sc->id_servicio=$dataObject->id_servicio;
            $sc->id_cliente=$dataObject->id_cliente;
            $sc->precio=$dataObject->precio;
            $sc->duracion_min=$dataObject->duracion_min;
            $sc->fecha_hora=$dataObject->fecha_hora;
            $sc->estado=$dataObject->estado;
            $sc->delete();

            $sc = new Success($sc);
            return $sc->send();
        }catch(\Exception $e){
            $sc = new failure(400, "Error al crear cita verifica tus datos. info error:E-081927");
            return $sc ->Send();
        }
    }
    
    function actualizarServicioCita()
    {
        try {
            $JSONData = file_get_contents("php://input");
            $dataObject = json_decode($JSONData);
    
            // Checking if id is provided
            if (!property_exists($dataObject, 'id')) {
                throw new \Exception("Debe proporcionar el ID del servicio_cita para actualizar");
            }
    
            $id = $dataObject->id;
    
            $sql = "UPDATE servicio_cita SET ";
            $values = [];
            
            if (property_exists($dataObject, 'id_servicio')) {
                $sql .= "id_servicio = :id_servicio, ";
                $values[':id_servicio'] = $dataObject->id_servicio;
            }
            if (property_exists($dataObject, 'id_cita')) {
                $sql .= "id_cita = :id_cita, ";
                $values[':id_cita'] = $dataObject->id_cita;
            }
            if (property_exists($dataObject, 'precio')) {
                $sql .= "precio = :precio, ";
                $values[':precio'] = $dataObject->precio;
            }
            if (property_exists($dataObject, 'duracion_min')) {
                $sql .= "duracion_min = :duracion_min, ";
                $values[':duracion_min'] = $dataObject->duracion_min;
            }
            if (property_exists($dataObject, 'fecha_hora')) {
                $sql .= "fecha_hora= :fecha_hora, ";
                $values[':fecha_hora'] = $dataObject->fecha_hora;
            }
    
            // Remove trailing comma and add WHERE clause
            $sql = rtrim($sql, ', ') . " WHERE id = :id";
            $values[':id'] = $id;
    
            $stmt = $this->conexion->getPDO()->prepare($sql);
            $stmt->execute($values);
    
            $rowsAffected = $stmt->rowCount();
    
            if ($rowsAffected === 0) {
                throw new \Exception("No se encontró el servicio con el ID proporcionado");
            }
    
            header('Content-Type: application/json');
            echo json_encode(['message' => 'servicio actualizado exitosamente.']);
            http_response_code(200);
    
        } catch (\Exception $e) {
            $errorResponse = ['message' => "Error en el servidor: " . $e->getMessage()];
            header('Content-Type: application/json');
            echo json_encode($errorResponse);
            http_response_code(500);
        }
    }
// funcion para actualizar la fecha_fin de servicio_cita 
      function actualizarRegistroCita()
    {
        try {
            $JSONData = file_get_contents("php://input");
            $dataObject = json_decode($JSONData);
    
            // Checking if id is provided
            if (!property_exists($dataObject, 'id')) {
                throw new \Exception("Debe proporcionar el ID del servicio_cita para actualizar");
            }
    
            $id = $dataObject->id;
    
            $sql = "UPDATE registro_citas SET ";
            $values = [];
            
            if (property_exists($dataObject, 'cliente')) {
                $sql .= "cliente = :cliente, ";
                $values[':cliente'] = $dataObject->cliente;
            }
            if (property_exists($dataObject, 'costo')) {
                $sql .= "costo = :costo, ";
                $values[':costo'] = $dataObject->costo;
            }
            if (property_exists($dataObject, 'fecha_hora_inicio')) {
                $sql .= "fecha_hora_inicio = :fecha_hora_inicio, ";
                $values[':fecha_hora_inicio'] = $dataObject->fecha_hora_inicio;
            }
            if (property_exists($dataObject, 'fecha_hora_fin')) {
                $sql .= "fecha_hora_fin = :fecha_hora_fin, ";
                $values[':fecha_hora_fin'] = $dataObject->fecha_hora_fin;
            }
            if (property_exists($dataObject, 'duracion_total')) {
                $sql .= "duracion_total= :duracion_total, ";
                $values[':duracion_total'] = $dataObject->duracion_total;
            }
            if (property_exists($dataObject, 'estado')) {
                $sql .= "estado = :estado, ";
                $values[':estado'] = $dataObject->estado;
            }
            if (property_exists($dataObject, 'fecha_cita')) {
                $sql .= "fecha_cita = :fecha_cita, ";
                $values[':fecha_cita'] = $dataObject->fecha_cita;
            }
            if (property_exists($dataObject, 'desc_rechazo')) {
                $sql .= "desc_rechazo = :desc_rechazo, ";
                $values[':desc_rechazo'] = $dataObject->desc_rechazo;
            }
    
            // Remove trailing comma and add WHERE clause
            $sql = rtrim($sql, ', ') . " WHERE id = :id";
            $values[':id'] = $id;
    
            $stmt = $this->conexion->getPDO()->prepare($sql);
            $stmt->execute($values);
    
            $rowsAffected = $stmt->rowCount();
    
            if ($rowsAffected === 0) {
                throw new \Exception("No se encontró el servicio con el ID proporcionado");
            }
    
            header('Content-Type: application/json');
            echo json_encode(['message' => 'servicio actualizado exitosamente.']);
            http_response_code(200);
    
        } catch (\Exception $e) {
            $errorResponse = ['message' => "Error en el servidor: " . $e->getMessage()];
            header('Content-Type: application/json');
            echo json_encode($errorResponse);
            http_response_code(500);
        }
    }
// funcion para actualizar fecha_inicio y fecha_fin de servicio_cita 
    function updataFechasCitas (){
        try {
            $JSONData = file_get_contents("php://input");
            $dataObject = json_decode($JSONData);

            $fecha_fin = $dataObject->fecha_fin;   
            $fecha_inicio = $dataObjecto->fecha_inicio;

            $service = $this->updateFechasQuerry($fecha_inicio, $fecha_fin);
            $response = ['data' => $service];


            header('Content-Type: application/json');
            echo json_encode(['message' => 'Procedimiento ejecutado correctamente', 'data' => $response]);
            
        } catch (\Exception $e) {
            $errorResponse = ['message' => "Error en el servidor: " . $e->getMessage()];
            header('Content-Type: application/json');
            echo json_encode($errorResponse);
            http_response_code(500);
        }
    }

    function updateFechasQuerry($fecha_inicio, $fecha_fin) {
        // aca va la consulta que hacer el update 
        $r = table::queryParams("CALL Consulta(:fecha_inicio, :fecha_fin)",
            
            [
                'estado'=> $estado,
            ]
        
        );
        return $r;

    }
    

}
