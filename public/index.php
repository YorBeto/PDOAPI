<?php

namespace proyecto;
require("../vendor/autoload.php");


use PDO;
use proyecto\Models\User;
use proyecto\Response\Failure;
use proyecto\Response\Success;
use proyecto\Models\clientes;
use proyecto\Models\inbody_citas;
use proyecto\Models\productos_servicios;
use proyecto\Models\Categorias_productos;
use proyecto\Models\Personas;
use proyecto\Models\Empleados;
use proyecto\Models\Clases;
use proyecto\Controller\PersonasController;
use proyecto\Controller\MostrarSociosController;
use proyecto\Controller\LoginController;
use proyecto\Controller\LoginSociosController;
use proyecto\Controller\ProductosController;
use proyecto\Controller\EmpleadosController;
use proyecto\Controller\CarritoController;
// Metodo header para poder resivir solicitudes de cualquier dominio //
//Router::headers();

//Metodos post//


Router::post('/registro', [PersonasController::class, "registroclientes"]);
Router::post('/loginSocios', [LoginSociosController::class, "loginsocios"]);
Router::post('/insertarproducto', [ProductosController::class, "insertarProducto"]);
Router::post('/producto/actualizar', [ProductosController::class, "actualizarProducto"]);
Router::post('/registro',[PersonasController::class,"registroclientes"]);
Router::post('/registroEmpleados',[EmpleadosController::class,"registroempleados"]);
Router::post('/loginClientes',[LoginController::class,"login"]);
Router::post('/loginSocios',[LoginSociosController::class,"loginsocios"]);

Router::post('/orden/crear', [CarritoController::class, "crearOrdenVenta"]);
Router::post('/producto/agregar', [CarritoController::class, "agregarProductoDetalle"]);
Router::post('/pago/registrar', [CarritoController::class, "registrarPago"]);

// Rutas DELETE
Router::delete('/producto/eliminar', [ProductosController::class, "eliminarProducto"]);
Router::delete('/empleado/eliminar', [EmpleadosController::class, "eliminarEmpleado"]);
// Metodos get //
Router::get('/prueba', function () {
    $data = [
        'nombre' => 'Juan',
        'edad' => 25,
        'pais' => 'México'
    ];

    $r= new Success($data);
    return $r->Send();
});
Router::get('/empleados', [Empleados::class, "mostrarEmpleados"]);
Router::get('/socios', [MostrarSociosController::class, "mostrarsocios"]);
Router::get('/citas', [inbody_citas::class, "mostrarcitas"]);
Router::get('/categorias', [Categorias_productos::class, "obtenerCategorias"]);
Router::get('/crearpersona', [crearPersonaController::class, "crearPersona"]);
Router::get('/productos', [productos_servicios::class, "mostrarProductos"]);
Router::get('/productosinicio', [productos_servicios::class, "productosinicio"]);
Router::get('/producto', [ProductosController::class, "obtenerProductoPorId"]);
Router::get('/clases', [Clases::class, "mostrarClases"]);
Router::get('/empleado/obtener', [EmpleadosController::class, "obtenerEmpleadoPorId"]);
Router::get('/usuario/buscar/$id', function ($id) {

    $user= User::find($id);
    if(!$user)
    {
        $r= new Failure(404,"no se encontro el usuario");
        return $r->Send();
    }
   $r= new Success($user);
    return $r->Send();


});
