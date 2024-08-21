<?php

namespace proyecto\Models;
use proyecto\Models\Models;
use proyecto\Response\Success;


class Empleados extends Models{

    protected $table = "Empleados";
    protected $fillable = ["ID_EMPLEADO", "ID_PERSONA", "FECHA_REGISTRO", "DIRECCION", "CURP", "RFC", "NUMERO_SEGURO"];

    public function mostrarEmpleados(){
        $empleados = new Table();
        $todoslosempleados = $empleados->query("SELECT E.ID_EMPLEADO AS ID, CONCAT(P.NOMBRE, ' ', P.APELLIDO) AS 'NOMBRE', 
                                                P.CORREO, P.TELEFONO, E.DIRECCION, E.CURP, E.RFC, E.NUMERO_SEGURO AS 'NUMERO DE SEGURO'
                                                FROM EMPLEADOS E INNER JOIN PERSONA P ON E.ID_PERSONA = P.ID_PERSONA
                                                INNER JOIN USUARIOS U ON P.ID_USUARIO = U.ID_USUARIO");
        $success = new Success($todoslosempleados);
        return $success->send();
    }
}
