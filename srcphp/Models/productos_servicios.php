<?php
namespace proyecto\Models;
use proyecto\Models\Models;
use proyecto\Response\Success;
use proyecto\Models\Table;

class productos_servicios extends Models
{
    protected $table = "PRODUCTOS_SERVICIOS";
    protected $id = "ID_PRODUCTO";
    protected $fillable = ['ID_PRODUCTO', 'NOMBRE', 'DESCRIPCION', 'PRECIO', 'STOCK', 'ID_CATEGORIA', 'IMAGEN'];

    public function mostrarProductos(){
        $producto = new Table();
        $todoslosproductos = $producto->query("SELECT productos_servicios.ID_PRODUCTO AS ID, productos_servicios.NOMBRE, productos_servicios.DESCRIPCION,
                                                productos_servicios.PRECIO, productos_servicios.STOCK, categoria_productos.NOMBRE AS CATEGORIA, productos_servicios.IMAGEN
                                                FROM categoria_productos INNER JOIN productos_servicios ON categoria_productos.ID_CATEGORIA = productos_servicios.ID_CATEGORIA");

        $success = new Success($todoslosproductos);
        return $success->send();
    }

    public function mostrarProductosAdmin(){
        $producto = new Table();
        $todoslosproductos = $producto->query("SELECT productos_servicios.NOMBRE, productos_servicios.DESCRIPCION,
                                                productos_servicios.PRECIO, productos_servicios.STOCK, categoria_productos.NOMBRE AS CATEGORIA
                                                FROM categoria_productos INNER JOIN productos_servicios ON categoria_productos.ID_CATEGORIA = productos_servicios.ID_CATEGORIA");

        $success = new Success($todoslosproductos);
        return $success->send();
    }

    public function mostrarOrdenes(){
        $orden = new Table();
        $ordenes = $orden->query("SELECT 
                                    OV.ID_ORDEN AS CLAVE_ORDEN,
                                    P.NOMBRE AS CLIENTE,
                                    GROUP_CONCAT(PS.NOMBRE SEPARATOR ', ') AS 'PRODUCTOS COMPRADOS',
                                    SUM(DV.TOTAL) AS MONTO_TOTAL,
                                    PA.ESTADO_ENTREGA AS 'ESTADO DE ENTREGA'
                                FROM ORDEN_VENTA OV
                                INNER JOIN CLIENTES C ON OV.ID_CLIENTE = C.ID_CLIENTES
                                INNER JOIN PERSONA P ON C.ID_PERSONA = P.ID_PERSONA
                                INNER JOIN DETALLE_VENTA DV ON OV.ID_ORDEN = DV.ID_ORDEN
                                INNER JOIN PRODUCTOS_SERVICIOS PS ON DV.ID_PRODUCTO = PS.ID_PRODUCTO
                                INNER JOIN PAGOS PA ON OV.ID_ORDEN = PA.ID_ORDEN
                                WHERE PA.ESTADO_ENTREGA = 'PENDIENTE'
                                GROUP BY OV.ID_ORDEN, P.NOMBRE
                                ORDER BY OV.ID_ORDEN;");

        $success = new Success($ordenes);
        return $success->send();
    }

    public function mostrarOrdenesEntregadas(){
        $orden = new Table();
        $ordenes = $orden->query("SELECT 
                                    OV.ID_ORDEN AS CLAVE_ORDEN,
                                    P.NOMBRE AS CLIENTE,
                                    GROUP_CONCAT(PS.NOMBRE SEPARATOR ', ') AS 'PRODUCTOS COMPRADOS',
                                    SUM(DV.TOTAL) AS MONTO_TOTAL,
                                    PA.ESTADO_ENTREGA AS 'ESTADO DE ENTREGA'
                                FROM ORDEN_VENTA OV
                                INNER JOIN CLIENTES C ON OV.ID_CLIENTE = C.ID_CLIENTES
                                INNER JOIN PERSONA P ON C.ID_PERSONA = P.ID_PERSONA
                                INNER JOIN DETALLE_VENTA DV ON OV.ID_ORDEN = DV.ID_ORDEN
                                INNER JOIN PRODUCTOS_SERVICIOS PS ON DV.ID_PRODUCTO = PS.ID_PRODUCTO
                                INNER JOIN PAGOS PA ON OV.ID_ORDEN = PA.ID_ORDEN
                                WHERE PA.ESTADO_ENTREGA = 'ENTREGADO'
                                GROUP BY OV.ID_ORDEN, P.NOMBRE
                                ORDER BY OV.ID_ORDEN;");

        $success = new Success($ordenes);
        return $success->send();
    }

    public function ventasPorTiempo($filtroTiempo = 'mes') {
        $producto = new Table();
        
        // Definir la condición de tiempo según el filtro
        switch ($filtroTiempo) {
            case 'semana':
                $condicionTiempo = "OV.FECHA_ORDEN BETWEEN DATE_SUB(NOW(), INTERVAL 1 WEEK) AND NOW()";
                break;
            case 'mes':
                $condicionTiempo = "OV.FECHA_ORDEN BETWEEN DATE_FORMAT(NOW() ,'%Y-%m-01') AND LAST_DAY(NOW())";
                break;
            case 'anio':
                $condicionTiempo = "OV.FECHA_ORDEN BETWEEN DATE_FORMAT(NOW() ,'%Y-01-01') AND DATE_FORMAT(NOW(),'%Y-12-31')";
                break;
            case 'historico':
                $condicionTiempo = "1=1"; // No filtra por tiempo, muestra todo el historial
                break;
            default:
                $condicionTiempo = "OV.FECHA_ORDEN BETWEEN DATE_FORMAT(NOW() ,'%Y-%m-01') AND LAST_DAY(NOW())"; // Valor por defecto es el mes actual
        }
    
        // Consulta SQL actualizada con el filtro de tiempo dinámico
        $ventas = $producto->query("SELECT 
                                        PS.NOMBRE AS PRODUCTO,
                                        PS.STOCK,
                                        CP.NOMBRE AS CATEGORIA,
                                        SUM(DV.CANTIDAD) AS CANTIDAD_VENDIDA,
                                        SUM(DV.TOTAL) AS MONTO_RECAUDADO
                                    FROM PRODUCTOS_SERVICIOS PS
                                    INNER JOIN CATEGORIA_PRODUCTOS CP ON PS.ID_CATEGORIA = CP.ID_CATEGORIA
                                    INNER JOIN DETALLE_VENTA DV ON PS.ID_PRODUCTO = DV.ID_PRODUCTO
                                    INNER JOIN ORDEN_VENTA OV ON DV.ID_ORDEN = OV.ID_ORDEN
                                    WHERE $condicionTiempo
                                    GROUP BY PS.NOMBRE, PS.STOCK, CP.NOMBRE
                                    ORDER BY MONTO_RECAUDADO DESC");
    
        $success = new Success($ventas);
        return $success->send();
    }
    
    public function ordenesPorTiempo($filtroTiempo = 'mes') {
        $orden = new Table();
    
        // Definir la condición de tiempo según el filtro
        switch ($filtroTiempo) {
            case 'semana':
                $condicionTiempo = "OV.FECHA_ORDEN BETWEEN DATE_SUB(NOW(), INTERVAL 1 WEEK) AND NOW()";
                break;
            case 'mes':
                $condicionTiempo = "OV.FECHA_ORDEN BETWEEN DATE_FORMAT(NOW() ,'%Y-%m-01') AND LAST_DAY(NOW())";
                break;
            case 'anio':
                $condicionTiempo = "OV.FECHA_ORDEN BETWEEN DATE_FORMAT(NOW() ,'%Y-01-01') AND DATE_FORMAT(NOW(),'%Y-12-31')";
                break;
            case 'historico':
                $condicionTiempo = "1=1"; // No filtra por tiempo, muestra todo el historial
                break;
            default:
                $condicionTiempo = "OV.FECHA_ORDEN BETWEEN DATE_FORMAT(NOW() ,'%Y-%m-01') AND LAST_DAY(NOW())"; // Valor por defecto es el mes actual
        }

        $ventas = $orden->query("SELECT 
                                    OV.ID_ORDEN AS CLAVE_ORDEN,
                                    P.NOMBRE AS CLIENTE,
                                    GROUP_CONCAT(PS.NOMBRE SEPARATOR ', ') AS PRODUCTOS_COMPRADOS,
                                    SUM(DV.TOTAL) AS MONTO_TOTAL
                                FROM ORDEN_VENTA OV
                                INNER JOIN CLIENTES C ON OV.ID_CLIENTE = C.ID_CLIENTES
                                INNER JOIN PERSONA P ON C.ID_PERSONA = P.ID_PERSONA
                                INNER JOIN DETALLE_VENTA DV ON OV.ID_ORDEN = DV.ID_ORDEN
                                INNER JOIN PRODUCTOS_SERVICIOS PS ON DV.ID_PRODUCTO = PS.ID_PRODUCTO
                                WHERE $condicionTiempo
                                GROUP BY OV.ID_ORDEN, P.NOMBRE
                                ORDER BY OV.ID_ORDEN");

        $success = new Success($ventas);
        return $success->send();
    }

    public function membresiasPorTiempo($filtroTiempo = 'mes') {
        $membresia = new Table();
        
        // Definir la condición de tiempo según el filtro
        switch ($filtroTiempo) {
            case 'semana':
                $condicionTiempo = "OV.FECHA_ORDEN BETWEEN DATE_SUB(NOW(), INTERVAL 1 WEEK) AND NOW()";
                break;
            case 'mes':
                $condicionTiempo = "OV.FECHA_ORDEN BETWEEN DATE_FORMAT(NOW() ,'%Y-%m-01') AND LAST_DAY(NOW())";
                break;
            case 'anio':
                $condicionTiempo = "OV.FECHA_ORDEN BETWEEN DATE_FORMAT(NOW() ,'%Y-01-01') AND DATE_FORMAT(NOW(),'%Y-12-31')";
                break;
            case 'historico':
                $condicionTiempo = "1=1"; // No filtra por tiempo, muestra todo el historial
                break;
            default:
                $condicionTiempo = "OV.FECHA_ORDEN BETWEEN DATE_FORMAT(NOW() ,'%Y-%m-01') AND LAST_DAY(NOW())"; // Valor por defecto es el mes actual
        }

        $ventas = $membresia->query("SELECT 
                                        OV.ID_ORDEN as CLAVE_ORDEN,
                                        P.NOMBRE AS CLIENTE,
                                        PS.NOMBRE AS MEMBRESIA
                                    FROM ORDEN_VENTA OV
                                    INNER JOIN CLIENTES C ON OV.ID_CLIENTE = C.ID_CLIENTES
                                    INNER JOIN PERSONA P ON C.ID_PERSONA = P.ID_PERSONA
                                    INNER JOIN DETALLE_VENTA DV ON OV.ID_ORDEN = DV.ID_ORDEN
                                    INNER JOIN PRODUCTOS_SERVICIOS PS ON DV.ID_PRODUCTO = PS.ID_PRODUCTO
                                    WHERE PS.ID_CATEGORIA = 'CAT05' 
                                    AND $condicionTiempo");

        $success = new Success($ventas);
        return $success->send();
    }
}