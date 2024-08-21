<?php
namespace proyecto\Models;

use proyecto\Models\Models;
use proyecto\Response\Success;
use proyecto\Models\Table;

class Clases extends Models
{
    protected $table = "CLASES";
    protected $id = "ID_CLASE";
    protected $fillable = ['ID_CLASE', 'NOMBRE', 'HORA_CLASE', 'INSCRITOS'];

    public function mostrarClases(){
        $clases = new Table();
        $todaslasclases = $clases->query("SELECT ID_CLASE, NOMBRE, HORA_CLASE, INSCRITOS FROM CLASES;");

        $success = new Success($todaslasclases);
        return $success->send();
    }

    public function mostrarAlumnos(){
        $alumnos = new Table();
        $todaslosalumnos = $alumnos->query("SELECT SC.ID_SOCIO_CLASE,C.NOMBRE AS CLASE, 
                                            CONCAT(P.NOMBRE, ' ', P.APELLIDO) AS 'NOMBRE DEL ALUMNO'
                                            FROM CLASES C 
                                            JOIN SOCIOS_CLASES SC ON C.ID_CLASE = SC.ID_CLASE
                                            JOIN SOCIOS S ON SC.ID_SOCIO = S.ID_SOCIO
                                            JOIN CLIENTES CL ON S.ID_CLIENTE = CL.ID_CLIENTES
                                            JOIN PERSONA P ON CL.ID_PERSONA = P.ID_PERSONA
                                            ORDER BY P.APELLIDO, P.NOMBRE;");
        $success = new Success($todaslosalumnos);
        return $success->send();
    }

    public function registrarAsistencia($idSocioClase, $checkAsistencia) {
        $query = "CALL IngresarAsistencia('$idSocioClase', '$checkAsistencia')";
        return Table::query($query);
    }

    public function editarClase($idClase, $nombre, $horaClase) {
        $query = "CALL EditarClase('$idClase', '$nombre', '$horaClase')";
        return Table::query($query);
    }    
}