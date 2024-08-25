<?php

namespace proyecto\Controller;

use proyecto\Models\Table;
use proyecto\Response\Success;

class AdminEntregasController{

    public function AdminEntregas(){

        $input = file_get_contents('php://input');
        $dataObject = json_decode($input);


        $entrega=new Table();
        $confirmar=$entrega->query("SELECT 
    PAGOS.ID_ORDEN,
    PERSONA.NOMBRE AS CLIENTE,
    GROUP_CONCAT(PRODUCTOS_SERVICIOS.NOMBRE SEPARATOR ', ') AS PRODUCTOS,
    SUM(DETALLE_VENTA.TOTAL) AS MONTO,
    ORDEN_VENTA.FECHA_ORDEN,
    PAGOS.ESTADO_ENTREGA
FROM 
    PAGOS
JOIN 
    ORDEN_VENTA ON PAGOS.ID_ORDEN = ORDEN_VENTA.ID_ORDEN
JOIN 
    CLIENTES ON ORDEN_VENTA.ID_CLIENTE = CLIENTES.ID_CLIENTES
JOIN 
    PERSONA ON CLIENTES.ID_PERSONA = PERSONA.ID_PERSONA
JOIN 
    DETALLE_VENTA ON PAGOS.ID_ORDEN = DETALLE_VENTA.ID_ORDEN
JOIN 
    PRODUCTOS_SERVICIOS ON DETALLE_VENTA.ID_PRODUCTO = PRODUCTOS_SERVICIOS.ID_PRODUCTO
WHERE 
    PAGOS.ESTADO_ENTREGA = 'PENDIENTE'
GROUP BY 
    PAGOS.ID_ORDEN,
    PERSONA.NOMBRE,
    ORDEN_VENTA.FECHA_ORDEN,
    PAGOS.ESTADO_ENTREGA;");


    $success = new Success($confirmar);
    return $success->send();

    }

    public function ConfirmarEntrega(){

        $input = file_get_contents('php://input');
        $dataObject = json_decode($input);

        $ID_ORDEN = $dataObject->ID_ORDEN;


        $confirmacion=new Table();
        $estatus=$confirmacion->query("");
    }
}