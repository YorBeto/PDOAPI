<?php
namespace proyecto\Models;

use PDO;
use proyecto\Models\Table;
use proyecto\Response\Success;

class Direccion extends Models{
    public $id;
    public $calle;
    public $colonia;
    public $num_ext;
    public $codigo_postal;
    public $localidad;

    protected $filleable=[
        "calle",
        "colonia",
        "num_ext",
        "codigo_postal",
        "localidad",
    ];

    public $table = "direccion";
}