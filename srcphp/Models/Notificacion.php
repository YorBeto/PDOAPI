<?php
namespace proyecto\Models;

use PDO;
use proyecto\Models\Table;
use proyecto\Response\Success;

class Notificacion extends Models{
    public $id;
    public $info;
    public $estado;
    public $id_usuario;

    protected $filleable=[
        "info",
        "estado",
        "id_usuario",
    ];
    public $table="notificaciones";
}