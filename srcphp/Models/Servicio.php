<?php
namespace proyecto\Models;

use PDO;
use proyecto\Models\Table;
use proyecto\Response\Success;

class Servicio extends Models{
    public $id;
    public $nombre;
    public $precio;
    public $descripcion;
    public $img;
    public $duracion_min;
    public $categoria;
    public $activo;

    protected $filleable = [
        "nombre",
        "precio",
        "descripcion",
        "img",
        "duracion_min",
        "categoria",
        "activo",
    ];

    public $table="servicios";
}
