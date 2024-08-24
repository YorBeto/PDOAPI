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
use proyecto\Controller\CarritoController;
use proyecto\Controller\crearPersonaController;
use proyecto\Controller\ClasesController;
use proyecto\Controller\AsistenciasController;


// Metodo header para poder recibir solicitudes de cualquier dominio //
Router::headers();

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
Router::get('/empleado/obtener', [EmpleadosController::class, "obtenerEmpleadoPorId"]);
Router::get('/socios', [MostrarSociosController::class, "mostrarsocios"]);
Router::get('/categorias', [Categorias_productos::class, "obtenerCategorias"]);
Router::get('/crearpersona', [crearPersonaController::class, "crearPersona"]);
Router::get('/productos', [productos_servicios::class, "mostrarProductos"]);
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




// Metodos POST //
Router::post('/registro', [PersonasController::class, "registroclientes"]);
Router::post('/loginSocios', [LoginSociosController::class, "loginsocios"]);
Router::post('/loginClientes', [LoginController::class, "login"]);
Router::post('/insertarProducto', [ProductosController::class, "insertarproducto"]);
Router::post('/producto/actualizar', [ProductosController::class, "actualizarproducto"]);
Router::post('/registroEmpleados', [EmpleadosController::class, "registroempleados"]);
Router::post('/agregarclase', [ClasesController::class, "agregarClase"]);
Router::post('/editarclase', [ClasesController::class, "editarClase"]);
Router::post('/inscribir', [ClasesController::class, "inscripcionClases"]);
Router::post('/asistencia/registrar', [ClasesController::class, "registrarAsistencia"]);
Router::post('/orden/crear', [CarritoController::class, "crearOrdenVenta"]);
/*Router::post('/producto/agregar', [CarritoController::class, "agregarProductoDetalle"]);
Router::post('/pago/registrar', [CarritoController::class, "registrarPago"]);
Router::post('/carrito/compra', [CarritoController::class, "procesarCompra"]);
*/
Router::post('/compra', [ProductosController::class, "generarOrdenVenta"]);

// Rutas DELETE //
Router::delete('/producto/eliminar', [ProductosController::class, "eliminarProducto"]);
Router::delete('/empleado/eliminar', [EmpleadosController::class, "eliminarEmpleado"]);

// Ruta para manejar errores 404 //
Router::any('/404', '../views/404.php');

?>
