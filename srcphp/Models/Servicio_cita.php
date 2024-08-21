<?php

namespace proyecto\Models;

use PDO;
use proyecto\Models\Table;
use proyecto\Response\Success;

class Servicio_cita extends Models{
    public $id;
    public $id_servicio;
    public $id_cita;
    public $precio;
    public $duracion_min;
    public $fecha_hora;
    public $tipo;

    protected $filleable=[
        "id_servicio",
        "id_cita",
        "precio",
        "duracion_min",
        "fecha_hora",
        "tipo"
    ];

    public $table ="servicio_cita";

    public function citas(){
        $res=Table::query("select servicio_cita.id, servicios.nombre as Servicio, usuarios.nombre as Cliente,
        servicio_cita.precio,  servicio_cita.duracion_min, fecha_hora, servicio_cita.estado from servicio_cita
        inner join servicios on servicio_cita.id_servicio=servicios.id
        inner join usuarios on servicio_cita.id_cliente=usuarios.id;");
        $res=new Success($res);
        $res->Send();
    }
}
