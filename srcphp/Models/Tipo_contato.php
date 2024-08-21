<?php 
namespace proyecto\Models;

use PDO;
use proyecto\Models\Table;
use proyecto\Response\Success;

class Tipo_contacto extends Models{
    public $id;
    public $tipo;

    protected $filleable=[
        "tipo",
    ];
    public $table="tipo_contacto";
}