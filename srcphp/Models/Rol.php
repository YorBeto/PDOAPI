<?php
namespace proyecto\Models;

use PDO;
use proyecto\Models\Table;
use proyecto\Response\Success;

class Rol extends Models{
    public $id;
    public $rol;

    protected $filleable=[
        "rol",
    ];

    public $table="roles";
}