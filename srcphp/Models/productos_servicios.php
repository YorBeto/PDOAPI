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

    public function ventasDelMesActual(){
        $producto = new Table();
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
                                    WHERE OV.FECHA_ORDEN BETWEEN DATE_FORMAT(NOW() ,'%Y-%m-01') AND LAST_DAY(NOW())
                                    GROUP BY PS.NOMBRE, PS.STOCK, CP.NOMBRE
                                    ORDER BY MONTO_RECAUDADO DESC");

        $success = new Success($ventas);
        return $success->send();
    }

    public function ordenesDelMesActual(){
        $orden = new Table();
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
                                WHERE YEAR(OV.FECHA_ORDEN) = YEAR(CURDATE()) 
                                AND MONTH(OV.FECHA_ORDEN) = MONTH(CURDATE())
                                GROUP BY OV.ID_ORDEN, P.NOMBRE
                                ORDER BY OV.ID_ORDEN");

        $success = new Success($ventas);
        return $success->send();
    }

    public function membresiasDelMesActual(){
        $membresia = new Table();
        $ventas = $membresia->query("SELECT 
                                    OV.ID_ORDEN AS CLAVE_ORDEN,
                                    P.NOMBRE AS CLIENTE,
                                    PS.NOMBRE AS MEMBRESIA
                                FROM ORDEN_VENTA OV
                                INNER JOIN CLIENTES C ON OV.ID_CLIENTE = C.ID_CLIENTES
                                INNER JOIN PERSONA P ON C.ID_PERSONA = P.ID_PERSONA
                                INNER JOIN DETALLE_VENTA DV ON OV.ID_ORDEN = DV.ID_ORDEN
                                INNER JOIN PRODUCTOS_SERVICIOS PS ON DV.ID_PRODUCTO = PS.ID_PRODUCTO
                                WHERE PS.ID_CATEGORIA = 'CAT05' 
                                AND YEAR(OV.FECHA_ORDEN) = YEAR(CURDATE()) 
                                AND MONTH(OV.FECHA_ORDEN) = MONTH(CURDATE())
                                ");

        $success = new Success($ventas);
        return $success->send();
    }
}
