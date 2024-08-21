<?php

namespace proyecto\Models;

use proyecto\Models\Models;
use proyecto\Response\Success;
use proyecto\Models\Table;

class Categorias_productos Extends models {
    protected $table = "CATEGORIA_PRODUCTOS";
    protected $id = "ID_CATEGORIA";
    protected $filleable = ['ID_CATEGORIA', 'NOMBRE'];

    public function obtenerCategorias() {
        $categoria = new Table();
        $todaslascategorias = $categoria->query("SELECT ID_CATEGORIA, NOMBRE FROM CATEGORIA_PRODUCTOS");

        $success=new Success($todaslascategorias);
        return $success -> send();
    }
} 