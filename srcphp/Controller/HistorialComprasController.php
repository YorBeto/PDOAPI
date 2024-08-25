<?php

namespace proyecto\Controller;

use proyecto\Models\Table;
use proyecto\Response\Success;


class HistorialComprasController{


    public function historial(){
       
       
        $input = file_get_contents('php://input');
        $dataObject = json_decode($input);
    
            $idCliente = $dataObject->idCliente;
            

            $historial=new Table();
            $todoelhistorial=$historial->query("");

    }
}