<?php
namespace proyeto\Models;

use PDO;
use proyecto\Models\Table;
use proyecto\Response\Success;

class Contacto extends Models{
    public $id;
    public $dato;
    public $id_tipo_dato;

    protected $filleable = [
        "dato",
        "id_tipo_dato",
    ];

    public $table = "contacto";
}