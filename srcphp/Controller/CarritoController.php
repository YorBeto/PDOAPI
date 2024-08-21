<?php

namespace proyecto\Controller;

use proyecto\Models\Table;
use proyecto\Response\Success;
use proyecto\Response\Failure;

class CarritoController {

    public function crearOrdenVenta()
    {
        // Leer datos del cuerpo de la solicitud
        $JSONData = file_get_contents("php://input");
        $dataObject = json_decode($JSONData);

        // Verificar que las propiedades existen en el objeto
        if (!isset($dataObject->id_cliente) || !isset($dataObject->fecha_orden)) {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
            return;
        }

        // Obtener los datos del objeto JSON
        $id_cliente = $dataObject->id_cliente;
        $fecha_orden = $dataObject->fecha_orden;

        // Llamar al procedimiento almacenado para crear la orden de venta
        $query = "CALL CrearOrdenVenta(
            '$id_cliente', 
            '$fecha_orden',
            @p_id_orden
        )";

        try {
            // Ejecutar la consulta
            Table::query($query);

            // Obtener el ID de la nueva orden
            $resultados = Table::query("SELECT @p_id_orden AS id_orden");
            $id_orden = $resultados[0]->id_orden;

            $r = new Success(['success' => true, 'message' => 'Orden creada exitosamente', 'id_orden' => $id_orden]);
            return $r->send();
        } catch (\Exception $e) {
            $r = new Failure(500, 'Error en la consulta: ' . $e->getMessage());
            return $r->send();
        }
    }

    
    

    public function agregarProductoCarrito()
    {
        // Leer datos del cuerpo de la solicitud
        $JSONData = file_get_contents("php://input");
        $dataObject = json_decode($JSONData);

        // Verificar que las propiedades existen en el objeto
        if (!isset($dataObject->id_orden) || !isset($dataObject->id_producto) || !isset($dataObject->cantidad)) {
            $r = new Failure(400, 'Datos incompletos');
            return $r->send();
        }

        // Obtener los datos del objeto JSON
        $id_orden = $dataObject->id_orden;
        $id_producto = $dataObject->id_producto;
        $cantidad = $dataObject->cantidad;

        // Llamar al procedimiento almacenado para agregar el producto al carrito
        $query = "CALL AgregarProductoCarrito('$id_orden', '$id_producto', $cantidad)";

        try {
            // Ejecutar la consulta
            Table::query($query);

            // Confirmar que la operación fue exitosa
            $r = new Success(['success' => true, 'message' => 'Producto agregado al carrito exitosamente']);
            return $r->send();
        } catch (\Exception $e) {
            $r = new Failure(500, 'Error en la consulta: ' . $e->getMessage());
            return $r->send();
        }
    }

    public function registrarPago() {
        // Leer datos del cuerpo de la solicitud
        $JSONData = file_get_contents("php://input");
        $dataObject = json_decode($JSONData);

        // Verificar que las propiedades existen en el objeto
        if (!isset($dataObject->id_orden) || !isset($dataObject->monto) || !isset($dataObject->metodo_pago)) {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
            return;
        }

        // Obtener los datos del objeto JSON
        $id_orden = $dataObject->id_orden;
        $monto = $dataObject->monto;
        $metodo_pago = $dataObject->metodo_pago;

        // Llamar al procedimiento almacenado para registrar el pago
        $query = "CALL RegistrarPago('$id_orden', '$monto', '$metodo_pago')";

        // Ejecutar la consulta
        try {
            $resultados = Table::query($query);
            if ($resultados) {
                $r = new Success(['success' => true, 'message' => 'Pago registrado exitosamente']);
                return $r->send();
            } else {
                $r = new Failure(500, 'Error al registrar el pago');
                return $r->send();
            }
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error en la consulta: ' . $e->getMessage()]);
            return;
        }
    }
}
