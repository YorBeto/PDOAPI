<?php
namespace proyecto\Models;

use proyecto\Models\Models;
use proyecto\Response\Success;
use proyecto\Models\Table;
use PDO;
use PDOException;


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

    public function mostrarAsistencia()
    {
        $alumnos = new Table();
        $todoslosalumnos = $alumnos->query(
                        "SELECT 
                            socios.id_socio,
                            persona.nombre, persona.apellido,
                            clases.nombre AS nombre_clase,
                            clases.hora_clase,
                            SUM(CASE WHEN asistencias.check_asistencia = 1 THEN 1 ELSE 0 END) AS asistencias,
                            SUM(CASE WHEN asistencias.check_asistencia = 0 THEN 1 ELSE 0 END) AS faltas
                        FROM 
                            persona
                        INNER JOIN 
                            clientes ON persona.id_persona = clientes.id_persona
                        INNER JOIN 
                            socios ON socios.id_cliente = clientes.id_clientes
                        INNER JOIN 
                            socios_clases ON socios_clases.id_socio = socios.id_socio
                        INNER JOIN 
                            asistencias ON asistencias.id_socio_clase = socios_clases.id_socio_clase
                        INNER JOIN 
                            clases ON socios_clases.id_clase = clases.id_clase
                        WHERE 
                            asistencias.fecha >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
                        GROUP BY 
                            socios.id_socio,
                            persona.nombre,
                            persona.apellido,
                            clases.nombre,
                            clases.hora_clase;");

        $success = new Success($todoslosalumnos);
        return $success->send();
    }
}
