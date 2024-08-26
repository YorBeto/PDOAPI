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
        $id = $_GET['id'] ?? '';
    
        if (empty($id)) {
            echo json_encode(['success' => false, 'message' => 'ID del producto no proporcionado']);
            return;
        }
    
        // Verificar si el producto existe antes de eliminar
        $producto = Table::query("SELECT * FROM PRODUCTOS_SERVICIOS WHERE ID_PRODUCTO = '$id'");
        if (!$producto) {
            echo json_encode(['success' => false, 'message' => 'El producto no existe']);
            return;
        }
    
        // Ejecutar consulta para eliminar el producto
        $query = "DELETE FROM PRODUCTOS_SERVICIOS WHERE ID_PRODUCTO = '$id'";
    
        try {
            Table::query($query);
            echo json_encode(['success' => true, 'message' => 'Producto eliminado con éxito']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el producto: ' . $e->getMessage()]);
        }
    }
    public function generarOrdenVenta() {
        
           $input = file_get_contents('php://input');
    $dataObject = json_decode($input);

        $idCliente = $dataObject->idCliente;
        $idProducto = $dataObject->idProducto;
        $Cantidad = $dataObject->Cantidad;
        $FormaPago = $dataObject->FormaPago;

        // Preparar la consulta para llamar al procedimiento almacenado
        $query = "CALL GenerarOrdenVenta(
           '$idCliente',
           '$idProducto',
           '$Cantidad',
           '$FormaPago'
        )";

        // Ejecutar la consulta y manejar la respuesta
        try {
            Table::query($query);
            $respuesta = new Success(['success' => true, 'message' => 'Orden de venta generada exitosamente.']);
            return $respuesta->send();
        } catch (Exception $e) {
            // Manejar errores específicos del procedimiento almacenado
            // Por ejemplo, errores de stock insuficiente
            echo json_encode(['success' => false, 'message' => 'Error al generar la orden de venta: ' . $e->getMessage()]);
            return;
        }
    }

    public function editarproductos() {

        $JSONData = file_get_contents("php://input");
        $data = json_decode ($JSONData, true);

        $idProducto= $data ['ID_PRODUCTO'];

        $stmt = $this -> PDO() -> prepare ("UPDATE productos_servicios SET NOMBRE = :NOMBRE, DESCRIPCION = :DESCRIPCION, PRECIO = :PRECIO, STOCK = :STOCK WHERE ID_PRODUCTO = :ID_PRODUCTO");

        $stmt->bindParam(':NOMBRE', $data['NOMBRE']);
        $stmt->bindParam(':DESCRIPCION', $data['DESCRIPCION']);
        $stmt->bindParam(':PRECIO', $data['PRECIO']);
        $stmt->bindParam(':STOCK', $data['STOCK']);
        $stmt->bindParam(':ID_PRODUCTO', $idProducto);

        try {
            $stmt->execute();
            echo json_encode(['success' => true, 'message' => 'Producto editado con éxito']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error al editar el producto: ' . $e->getMessage()]);
        }

        if (isset($data['ID_CATEGORIA'])){
            $stmtCATEGORIA = $this->PDO()->prepare("UPDATE categoria_productos SET NOMBRE = :NOMBRE WHERE ID_PRODUCTO = :ID_PRODUCTO ");
            $stmtCATEGORIA->bindParam(':NOMBRE', $data['NOMBRE']);
            $stmtCATEGORIA->bindParam(':ID_PRODUCTO', $idProducto);

            $stmtCATEGORIA->execute();

        }

    }
    private function PDO() {
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=arsenal_gym', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            return json_encode(['error' => 'Error de conexión: ' . $e->getMessage()]);
        }
    }
    public function marcarComoEntregada()
    {
        
        $input = file_get_contents('php://input');
        $dataObject = json_decode($input);

        $idPago = $dataObject->idPago;

        // Preparar la consulta para llamar al procedimiento almacenado
        $query = "CALL CambiarEstadoEntregaAEntregado(
           '$idPago'
        )";

        // Ejecutar la consulta y manejar la respuesta
        try {
            Table::query($query);
            $respuesta = new Success(['success' => true, 'message' => 'Orden de venta generada exitosamente.']);
            return $respuesta->send();
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error al generar la orden de venta: ' . $e->getMessage()]);
            return;
        }
    }
}