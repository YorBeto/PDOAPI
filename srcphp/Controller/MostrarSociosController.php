<?php

namespace proyecto\Controller;

use proyecto\Models\socios;
use proyecto\Models\Table;
use proyectto\Models\Models;

class MostrarSociosController{

    public function mostrarsocios(){

        $socios=new Table();
        $todoslossocios=$socios ->query("SELECT SOCIOS.ID_SOCIO,PERSONA.NOMBRE,SOCIOS.MEMBRESIA,
        SOCIOS.FECHA_INICIO, SOCIOS.FECHA_FIN,SOCIOS.ESTADO_DE_MEMB
        FROM PERSONA INNER JOIN CLIENTES ON PERSONA.ID_PERSONA = CLIENTES.ID_PERSONA
        INNER JOIN SOCIOS ON SOCIOS.ID_CLIENTE = CLIENTES.ID_CLIENTES
        WHERE SOCIOS.ESTADO_DE_MEMB = 'ACTIVO'");


        return Models::sendCorrect($todoslossocios);
    }
}



