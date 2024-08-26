<?php

namespace proyecto\Controller;

use proyecto\Models\Table;
use proyecto\Response\Success;


class HistorialComprasController{


    public function historial(){
       
       
        $input = file_get_contents('php://input');
        $dataObject = json_decode($input);
    
            $idCliente = $dataObject->idCliente;
            

            $historial=new Table();
            $todoelhistorial=$historial->query("SELECT PRODUCTOS_SERVICIOS.NOMBRE AS PRODUCTO, PRODUCTOS_SERVICIOS.PRECIO,DETALLE_VENTA.CANTIDAD, ORDEN_VENTA.FECHA_ORDEN AS FECHA,
               PRODUCTOS_SERVICIOS.IMAGEN FROM PAGOS JOIN ORDEN_VENTA ON PAGOS.ID_ORDEN = ORDEN_VENTA.ID_ORDEN JOIN CLIENTES ON ORDEN_VENTA.ID_CLIENTE = CLIENTES.ID_CLIENTES
                JOIN PERSONA ON CLIENTES.ID_PERSONA = PERSONA.ID_PERSONA
                JOIN DETALLE_VENTA ON PAGOS.ID_ORDEN = DETALLE_VENTA.ID_ORDEN
                JOIN PRODUCTOS_SERVICIOS ON DETALLE_VENTA.ID_PRODUCTO = PRODUCTOS_SERVICIOS.ID_PRODUCTO
                WHERE CLIENTES.ID_CLIENTES='$idCliente'");

        $success=new Success($todoelhistorial);
        return $success->send();

    }
}