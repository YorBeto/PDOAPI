<?php
namespace proyecto\Models;

use PDO;
use proyecto\Models\Table;
use proyecto\Response\Success;

class Info_local extends Models{
    public $id;
    public $nombre;
    public $descripcion;

    protected $filleable =[
        "nombre",
        "descripcion",
    ];

    public $table="info_local";
}