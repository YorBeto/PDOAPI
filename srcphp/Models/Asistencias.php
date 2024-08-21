<?php
namespace proyecto\Models;

use proyecto\Models\Models;
use proyecto\Response\Success;
use proyecto\Models\Table;

class Asistencias extends Models
{
    protected $table = "ASISTENCIAS";
    protected $id = "ID_ASISTENCIA";
    protected $fillable = ['ID_ASISTENCIA', 'ID_SOCIO_CLASE', 'FECHA', 'CHECK_ASISTENCIA'];

    public function mostrarAlumnos(){
        $alumnos = new Table();
        $todoslosalumnos = $alumnos->query("SELECT ID_CLASE, NOMBRE, HORA_CLASE, INSCRITOS FROM CLASES;");

        $success = new Success($todoslosalumnos);
        return $success->send();
    }
}

