<?php

namespace proyecto\Models;

use PDO;

class Orden {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function crearOrden($idOrden, $idCliente, $idEmpleado, $fechaOrden) {
        $sql = "INSERT INTO ordenes (ID_ORDEN, ID_CLIENTE, ID_EMPLEADO, FECHA_ORDEN) VALUES (:idOrden, :idCliente, :idEmpleado, :fechaOrden)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'idOrden' => $idOrden,
            'idCliente' => $idCliente,
            'idEmpleado' => $idEmpleado,
            'fechaOrden' => $fechaOrden,
        ]);
    }
}

class DetalleVenta {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function agregarDetalle($idOrden, $idProducto, $cantidad, $total) {
        $sql = "INSERT INTO detalle_ventas (ID_ORDEN, ID_PRODUCTO, CANTIDAD, TOTAL) VALUES (:idOrden, :idProducto, :cantidad, :total)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'idOrden' => $idOrden,
            'idProducto' => $idProducto,
            'cantidad' => $cantidad,
            'total' => $total,
        ]);
    }
}

class Pago {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function registrarPago($idPago, $idOrden, $formaPago, $estadoPago) {
        $sql = "INSERT INTO pagos (ID_PAGO, ID_ORDEN, FORMA_PAGO, ESTADO_PAGO) VALUES (:idPago, :idOrden, :formaPago, :estadoPago)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'idPago' => $idPago,
            'idOrden' => $idOrden,
            'formaPago' => $formaPago,
            'estadoPago' => $estadoPago,
        ]);
    }
}

