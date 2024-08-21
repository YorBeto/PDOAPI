<?php

namespace proyecto\Controller;

use proyecto\Models\productos_servicios;
use proyecto\Models\Table;
use proyecto\Response\Success;
use Exception;

class ProductosController 
{
    public function insertarProducto() {
        // Leer datos del cuerpo de la solicitud
        $nombre = $_POST['nombre'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $precio = $_POST['precio'] ?? '';
        $stock = $_POST['stock'] ?? '';
        $categoria = $_POST['categoria'] ?? '';
        
        // Verificar si se ha subido una imagen
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $imagenTmpPath = $_FILES['imagen']['tmp_name'];
            $imagenName = basename($_FILES['imagen']['name']);
            $imagenPath = 'uploads/' . $imagenName;
    
            if (move_uploaded_file($imagenTmpPath, $imagenPath)) {
                $imagenUrl = 'uploads/' . $imagenName;
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al mover el archivo de imagen']);
                return;
            }
        } else {
            $imagenUrl = ''; // O manejar el caso cuando no hay imagen
        }
    
        // Llamar al procedimiento almacenado para registrar el producto
        $query = "CALL RegistrarProductos(
            '$nombre', 
            '$descripcion', 
            '$precio', 
            '$stock', 
            '$categoria',
            '$imagenUrl'
        )";
    
        // Ejecutar la consulta
        try {
            $resultados = Table::query($query);
            $r = new Success(['success' => true, 'message' => 'Registro exitoso']);
            return $r->send();
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error en el registro: ' . $e->getMessage()]);
            return;
        }
    }     

    public function eliminarProducto() {
        // Leer datos del cuerpo de la solicitud
        $id = $_GET['id'] ?? '';

        if (empty($id)) {
            echo json_encode(['success' => false, 'message' => 'ID del producto no proporcionado']);
            return;
        }

        // Ejecutar consulta para eliminar el empleado
        $query = "DELETE FROM PRODUCTOS_SERVICIOS WHERE ID_PRODUCTO = '$id'";

        try {
            Table::query($query);
            $r = new Success(['success' => true, 'message' => 'Producto eliminado con éxito']);
            return $r->send();
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el producto: ' . $e->getMessage()]);
            return;
        }
    }
}