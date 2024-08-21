<?php
namespace proyecto\Controller;

use PDO;
use proyecto\Models\Models;
use proyecto\Models\Table;
use proyecto\Models\Servicio;
use proyecto\Response\Failure;
use proyecto\Response\Success;

use proyecto\Conexion;

class ServiciosController{
    private $conexion;
    public function __construct() {
        $this->conexion = new  Conexion("estetica","localhost","root","04063017");
            
    }
    
    public function servicios(){
        $res=Table::query("select * from servicios");
        $res=new Success($res);
        $res->Send();
    }

    public function servicios_act(){
        $res=Table::query("Select * from servicios where activo = 1");
        $res=new Success($res);
        $res->Send();
    }
    public function mostrar(){
        $res=Table::query("select id,nombre,
        duracion_min as duracion,precio,categoria
        from servicios where activo=1");
        $res=new Success($res);
        $res->Send();
    }

    public function crear_servicio(){
        try{
            $JSONData = file_get_contents('php://input');
            $dataObject = json_decode($JSONData);
            $sc = new Servicio();
            $sc->nombre=$dataObject->nombre;
            $sc->precio=$dataObject->precio;
            $sc->descripcion=$dataObject->descripcion;
            $sc->img=$dataObject->img;
            $sc->duracion_min=$dataObject->duracion_min;
            $sc->categoria=$dataObject->categoria;
            $sc->activo=$dataObject->activo;
            $sc->save();
            
            $r = new Success($sc);
            return $r->send();
        }catch(\Exception $e){
            $sc = new failure(400, "Error al crear cita verifica tus datos. info error:E-081927");
            return $sc ->Send();
        }
    }
    function actualizarServicio()
    {
        try {
            $JSONData = file_get_contents("php://input");
            $dataObject = json_decode($JSONData);
    
            // Checking if id is provided
            if (!property_exists($dataObject, 'id')) {
                throw new \Exception("Debe proporcionar el ID del servicio para actualizar");
            }
    
            $id = $dataObject->id;
    
            $sql = "UPDATE servicios SET ";
            $values = [];
            
            if (property_exists($dataObject, 'nombre')) {
                $sql .= "nombre = :nombre, ";
                $values[':nombre'] = $dataObject->nombre;
            }
            if (property_exists($dataObject, 'precio')) {
                $sql .= "precio = :precio, ";
                $values[':precio'] = $dataObject->precio;
            }
            if (property_exists($dataObject, 'descripcion')) {
                $sql .= "descripcion = :descripcion, ";
                $values[':descripcion'] = $dataObject->descripcion;
            }
            if (property_exists($dataObject, 'img')) {
                $sql .= "img = :img, ";
                $values[':img'] = $dataObject->img;
            }
            if (property_exists($dataObject, 'duracion_min')) {
                $sql .= "duracion_min= :duracion_min, ";
                $values[':duracion_min'] = $dataObject->duracion_min;
            }
            if (property_exists($dataObject, 'activo')) {
                $sql .= "activo = :activo, ";
                $values[':activo'] = $dataObject->activo;
            }
        
    
            // Remove trailing comma and add WHERE clause
            $sql = rtrim($sql, ', ') . " WHERE id = :id";
            $values[':id'] = $id;
    
            $stmt = $this->conexion->getPDO()->prepare($sql);
            $stmt->execute($values);
    
            $rowsAffected = $stmt->rowCount();
    
            if ($rowsAffected === 0) {
                throw new \Exception("No se encontró el cliente con el ID proporcionado");
            }
    
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Dato actualizado exitosamente.']);
            http_response_code(200);
    
        } catch (\Exception $e) {
            $errorResponse = ['message' => "Error en el servidor: " . $e->getMessage()];
            header('Content-Type: application/json');
            echo json_encode($errorResponse);
            http_response_code(500);
        }
    }
}