<?php
namespace proyecto\Controller;

use proyecto\Models\Table;
use poryecto\Models\Servicio;
use proyecto\Models\Registro_cita;
use proyecto\Response\Failure;
use proyecto\Response\Success;
use proyecto\Conexion;

class RegistroCitasController{
    private $conexion;
    public function __construct() {
        $this->conexion = new  Conexion("estetica","localhost","root","04063017");
            
    }
    public function registros(){
        $res=Table::query("   Select cita.id as id,(select id from usuarios where cita.cliente=id) as id_cliente,(select concat(nombre,' ',apellido_paterno,' ',apellido_materno) from usuarios where cita.cliente=id)as cliente, (select telefono from usuarios where cita.cliente=id) as telefono, (select user from usuarios where cita.cliente=id) as correo, cita.costo as costo, concat(DATE_FORMAT(cita.fecha_hora_inicio, '%Y/%m/%d %H:%i'),' - ',DATE_FORMAT(time(cita.fecha_hora_fin), '%H:%i')) as fecha,
        DATE_FORMAT(cita.fecha_cita, '%Y/%m/%d %H:%i') as fecha_de_creacion, cita.estado as estado
        from registro_citas as cita;");
        $res=new Success($res);
        $res->Send();
    }
    public function citas(){
        $res=Table::query('
        select id,fecha_hora_inicio as fechaInicio,duracion_total as duracionTotal,cliente as clienteId from registro_citas where estado = "confirmado";');
        $res=new Success($res);
        $res->Send();
    }

    public function citasCliente(){
        $res=Table::query('
        select id,fecha_hora_inicio as fechaInicio,duracion_total as duracionTotal,cliente as clienteId from registro_citas where estado = "confirmado" or estado = "sin_confirmar";');
        $res=new Success($res);
        $res->Send();
    }
  
    public function onlyservicios(){
        $res=Table::query('select servicio.id, servicios.id, servicios.nombre, servicios.duracion_min, servicios.precio, categorias.nombre as categoria 
        from servicios inner join categorias on servicios.categoria=categorias.id;');
        $res=new Success($res);
        $res->Send();
    }

    public function crear_registro_cita(){
        try{
            $JSONData = file_get_contents('php://input');
            $dataObject = json_decode($JSONData);
            $sc = new Registro_cita();
            $sc->cliente=$dataObject->cliente;
            $sc->costo=$dataObject->costo;
            $sc->fecha_hora_inicio=$dataObject->fecha_hora_inicio;
            $sc->fecha_hora_fin=$dataObject->fecha_hora_fin;
            $sc->duracion_total=$dataObject->duracion_total;
            $sc->estado=$dataObject->estado;
            $sc->fecha_cita=$dataObject->fecha_cita;
            $sc->tipo=$dataObject->tipo;
            $sc->save();
            
            $r = new Success($sc);
            return $r->send();
        }catch(\Exception $e){
            $sc = new failure(400, "Error al crear cita verifica tus datos. info error:E-081927");
            return $sc ->Send();
        }
    }
    
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