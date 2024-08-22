<?php

/*namespace proyecto\Controller;

use proyecto\Models\Orden;
use proyecto\Models\DetalleVenta;
use proyecto\Models\Pago;
use proyecto\Response\Success;
use proyecto\Response\Failure;

class Carrito {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function crearOrdenVenta($request) {
        // Extraer datos del request
        $data = json_decode($request->getBody(), true);
        $idCliente = $data['email']; // Usa el correo para buscar ID_CLIENTE
        $idOrden = 'OV00017'; // Generar ID de la orden (podrías hacerlo dinámico)
        $idEmpleado = 'E0001'; // ID del empleado por default
        $fechaOrden = date('Y-m-d H:i:s');

        $ordenModel = new Orden($this->db);
        $result = $ordenModel->crearOrden($idOrden, $idCliente, $idEmpleado, $fechaOrden);

        if ($result) {
            foreach ($data['productos'] as $producto) {
                $detalleVentaModel = new DetalleVenta($this->db);
                $detalleResult = $detalleVentaModel->agregarDetalle(
                    $idOrden,
                    $producto['ID_PRODUCTO'],
                    $producto['cantidad'],
                    $producto['PRECIO'] * $producto['cantidad']
                );

                if (!$detalleResult) {
                    return new Failure(500, "Error al crear el detalle de venta");
                }
            }

            return new Success(['idOrden' => $idOrden]);
        } else {
            return new Failure(500, "Error al crear la orden de venta");
        }
    }

    public function registrarPago($request) {
        $data = json_decode($request->getBody(), true);
        $idPago = 'PAG0003'; // Generar ID de pago (podrías hacerlo dinámico)
        $idOrden = $data['idOrden'];
        $formaPago = 'TARJETA';
        $estadoPago = 'LIQUIDADO';

        $pagoModel = new Pago($this->db);
        $result = $pagoModel->registrarPago($idPago, $idOrden, $formaPago, $estadoPago);

        if ($result) {
            return new Success(['idPago' => $idPago]);
        } else {
            return new Failure(500, "Error al registrar el pago");
        }
    }
}
*/