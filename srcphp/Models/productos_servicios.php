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
        $todoslosproductos = $producto->query("SELECT productos_servicios.ID_PRODUCTO AS ID, productos_servicios.NOMBRE, productos_servicios.DESCRIPCION,
                                                productos_servicios.PRECIO, productos_servicios.STOCK, categoria_productos.NOMBRE AS CATEGORIA
                                                FROM categoria_productos INNER JOIN productos_servicios ON categoria_productos.ID_CATEGORIA = productos_servicios.ID_CATEGORIA");

        $success = new Success($todoslosproductos);
        return $success->send();
    }    
}
