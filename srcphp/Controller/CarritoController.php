<?php

namespace proyecto\Controller;

use proyecto\Models\Table;
use proyecto\Response\Success;

class CarritoController{

    public function generarOrdenV($idCliente) {
        try {
            $ordenPendiente = Table::query("SELECT ID_ORDEN FROM ORDEN_VENTA WHERE ID_CLIENTE = '$idCliente' AND ESTATUS = 0");
            if (!empty($ordenPendiente)) {
                return $ordenPendiente[0]->ID_ORDEN;
            }

            $currentDate = date('Y-m-d H:i:s');
            Table::query("INSERT INTO ORDEN_VENTA (ID_CLIENTE, FECHA_ORDEN, ESTATUS) VALUES ('$idCliente', '$currentDate', 0)");
            $ordenId = Table::query("SELECT ID_ORDEN AS ID_ORDEN FROM ORDEN_VENTA WHERE ID_CLIENTE = '$idCliente' AND ESTATUS = 0");

            return $ordenId[0]->ID_ORDEN;
        } catch (Exception $e) {
            Table::rollback();
            return false;
        }
    }

    public function carritoEnviado(){
        $input = file_get_contents('php://input');
        $dataObject = json_decode($input);

        $idCliente = $dataObject->idCliente;
        $carrito = $dataObject->carrito;

        try {
            Table::beginTransaction();
            $ordenVenta = $this->generarOrdenV($idCliente);
            if ($ordenVenta == false) {
                echo json_encode(['success' => false, 'message' => 'Error al generar la orden de venta']);
                return;
            }

            foreach ($carrito as $producto) {
                $total = $producto->Cantidad * $producto->Precio;

                // Consulta para obtener la categoría del producto
                $categoriaProducto = Table::query("SELECT ID_CATEGORIA FROM PRODUCTOS_SERVICIOS WHERE ID_PRODUCTO = '$producto->idProducto'");
                
                // Determinar el estado de entrega
                $estadoEntrega = ($categoriaProducto[0]->ID_CATEGORIA == 'CAT05') ? 'ENTREGADO' : 'PENDIENTE';

                // Insertar el detalle de la venta
                Table::query("INSERT INTO DETALLE_VENTA 
                (ID_ORDEN, ID_PRODUCTO, CANTIDAD, TOTAL, ESTADO_ENTREGA) VALUES 
                ('$ordenVenta', '$producto->idProducto', '$producto->Cantidad', '$total', '$estadoEntrega')");
            }

            Table::query("INSERT INTO PAGOS (ID_ORDEN, FORMA_PAGO, ESTADO_PAGO) VALUES ('$ordenVenta', 'TARJETA', 'LIQUIDADO')");
            Table::query("UPDATE ORDEN_VENTA SET ESTATUS = 1 WHERE ID_ORDEN = '$ordenVenta'");
            Table::commit();

            echo json_encode(['success' => true, 'message' => 'Orden de venta generada exitosamente']);
        } catch (Exception $e) {
            Table::rollBack();
            echo json_encode(['success' => false, 'message' => 'Error al enviar el carrito: ' . $e->getMessage()]);
        }
    }

}