<?php
namespace proyecto\Models;

use PDO;
use proyecto\Models\Table;
use proyecto\Response\Success;

class Categoria extends Models{
    public $id;
    public $nombre;
    public $descripcion;
    public $img;
    public $activo;

    protected $filleable = [
        "nombre",
        "descripcion",
        "img",
        "activo",
    ];
    public $table = "categorias";
}