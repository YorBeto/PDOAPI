<?php

namespace proyecto;

require("../vendor/autoload.php");

use PDO;
use proyecto\Models\User;
use proyecto\Models\clientes;
use proyecto\Models\inbody_citas;
use proyecto\Models\productos_servicios;
use proyecto\Models\Categorias_productos;
use proyecto\Models\Personas;
use proyecto\Models\Empleados;
use proyecto\Models\Clases;
use proyecto\Models\Asistencias;
use proyecto\Response\Failure;
use proyecto\Response\Success;
use proyecto\Controller\PersonasController;
use proyecto\Controller\MostrarSociosController;
use proyecto\Controller\LoginController;
use proyecto\Controller\LoginSociosController;
use proyecto\Controller\ProductosController;
use proyecto\Controller\EmpleadosController;
use proyecto\Controller\crearPersonaController;
use proyecto\Controller\ClasesController;
use proyecto\Controller\CarritoController;
use proyecto\Controller\AdminEntregasController;
use proyecto\Controller\HistorialComprasController;

// Metodo header para poder recibir solicitudes de cualquier dominio //
Router::headers();

//Metodos post//
Router::post('/registro', [PersonasController::class, "registroclientes"]);
Router::post('/loginSocios', [LoginSociosController::class, "loginsocios"]);
Router::post('/insertarproducto', [ProductosController::class, "insertarProducto"]);
Router::post('/producto/actualizar', [ProductosController::class, "actualizarProducto"]);
Router::post('/registro',[PersonasController::class,"registroclientes"]);
Router::post('/registroEmpleados',[EmpleadosController::class,"registroempleados"]);
Router::post('/carrito',[CarritoController::class,"carritoEnviado"]);
Router::post('/miscompras', [HistorialComprasController::class, "historial"]);
Router::post('/pago', [CarritoController::class, "Carrito"]);


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
Router::get('/AdminProductos/Entregas', [AdminEntregasController::class, "AdminEntregas"]);
Router::get('/empleado/obtener', [EmpleadosController::class, "obtenerEmpleadoPorId"]);
Router::get('/socios', [MostrarSociosController::class, "mostrarsocios"]);
Router::get('/categorias', [Categorias_productos::class, "obtenerCategorias"]);
Router::get('/crearpersona', [crearPersonaController::class, "crearPersona"]);
Router::get('/productos', [productos_servicios::class, "mostrarProductos"]);

Router::get('/ventasproductos', function() {
    $filtroTiempo = $_GET['filtro'] ?? 'mes'; // Obtener filtro de tiempo de los parámetros de la URL, por defecto 'mes'
    (new productos_servicios())->ventasPorTiempo($filtroTiempo);
});
Router::get('/ordenes', function() {
    $filtroTiempo = $_GET['filtro'] ?? 'mes';
    (new productos_servicios())->ordenesPorTiempo($filtroTiempo);
});

Router::get('/membresias', function() {
    $filtroTiempo = $_GET['filtro'] ?? 'mes';
    (new productos_servicios())->membresiasPorTiempo($filtroTiempo);
});

Router::get('/adminproductos', [productos_servicios::class, "mostrarProductosAdmin"]);
Router::get('/productosinicio', [productos_servicios::class, "productosinicio"]);
Router::get('/producto', [ProductosController::class, "obtenerProductoPorId"]);
Router::get('/clases', [Clases::class, "mostrarClases"]);
Router::get('/alumnos', [Clases::class, "mostrarAlumnos"]);
Router::get('/alumnosclase', [Asistencias::class, "mostrarAlumnosClase"]);
Router::get('/asistencias', [Asistencias::class, "mostrarAsistencia"]);
Router::get('/usuario/buscar/$id', function ($id) {
    $user = User::find($id);
    if (!$user) {
        $r = new Failure(404, "no se encontró el usuario");
        return $r->Send();
    }
    $r = new Success($user);
    return $r->Send();
});
Router::get('/respuesta', [crearPersonaController::class, "response"]);

Router::get('/ventasproductos', [productos_servicios::class, "ventasDelMesActual"]);
Router::get('/ordenes', [productos_servicios::class, "ordenesDelMesActual"]);
Router::get('/membresias', [productos_servicios::class, "membresiasDelMesActual"]);


Router::get('/mostrarordenes', [productos_servicios::class, "mostrarOrdenes"]);
Router::get('/mostrarentregas', [productos_servicios::class, "mostrarOrdenesEntregadas"]);

// Metodos POST //
Router::post('/registro', [PersonasController::class, "registroclientes"]);
Router::post('/login', [LoginController::class, "login"]);
Router::post('/insertarProducto', [ProductosController::class, "insertarproducto"]);
Router::post('/producto/actualizar', [ProductosController::class, "actualizarproducto"]);
Router::post('/registroEmpleados', [EmpleadosController::class, "registroempleados"]);
Router::post('/agregarclase', [ClasesController::class, "agregarClase"]);
Router::post('/editarclase', [ClasesController::class, "editarClase"]);
Router::post('/inscribir', [ClasesController::class, "inscripcionClases"]);
Router::post('/asistencia/registrar', [ClasesController::class, "registrarAsistencia"]);

Router::post('/marcarcomoentregada', [ProductosController::class, "marcarComoEntregada"]);

// Rutas DELETE //
Router::delete('/producto/eliminar', [ProductosController::class, "eliminarProducto"]);
Router::delete('/empleado/eliminar', [EmpleadosController::class, "eliminarEmpleado"]);

// PUT
Router::put('/productoeditar',[ProductosController::class, "editarproductos"]);

// Ruta para manejar errores 404 //
Router::any('/404', '../views/404.php');

?>
