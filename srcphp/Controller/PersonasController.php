<?php

namespace proyecto\Controller;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use proyecto\Models\Table;
use proyecto\Response\Success;
use proyecto\Models\Personas;

class PersonasController {

    public function registroclientes() {
        // Leer datos del cuerpo de la solicitud
        $JSONData = file_get_contents("php://input");
        $dataObject = json_decode($JSONData);

        // Verificar que las propiedades existen en el objeto
        if (!isset($dataObject->nombre) || !isset($dataObject->apellidos) || 
            !isset($dataObject->fechaNacimiento) || !isset($dataObject->sexo) || 
            !isset($dataObject->correo) || !isset($dataObject->telefono) || 
            !isset($dataObject->contrasena)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
            return;
        }

        // Obtener los datos del objeto JSON
        $nombre = $dataObject->nombre;
        $apellidos = $dataObject->apellidos;
        $fechaNacimiento = $dataObject->fechaNacimiento;
        $sexo = $dataObject->sexo;
        $correo = $dataObject->correo;
        $telefono = $dataObject->telefono;
        $contrasena = $dataObject->contrasena;

        // Llamar al procedimiento almacenado para registrar a la persona
        $query = "CALL RegistrarPersonaLogin(
            '$nombre', 
            '$apellidos', 
            '$fechaNacimiento', 
            '$sexo', 
            '$correo', 
            '$telefono', 
            '$contrasena'
        )";

        // Ejecutar la consulta
        try {
            $resultados = Table::query($query);
            $this->enviarCorreoConfirmacion($correo, $nombre);
            header('Content-Type: application/json');
            $r = new Success(['success' => true, 'message' => 'Registro exitoso']);
            echo json_encode($r->send());
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Error en el registro: ' . $e->getMessage()]);
        }
    }

    private function enviarCorreoConfirmacion($correo, $nombre) {
        $mail = new PHPMailer(true);
    
        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Servidor SMTP de Gmail
            $mail->SMTPAuth = true;
            $mail->Username = 'lukepastranam12@gmail.com'; // Tu dirección de correo de Gmail
            $mail->Password = 'smoa gagw eeml kmyc'; // Contraseña de aplicación generada
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Configuración TLS
            $mail->Port = 587; // Puerto SMTP recomendado para STARTTLS
    
            // Configuración del correo
            $mail->setFrom('lukepastranam12@gmail.com', 'Arsenal Gym Bosque'); // Dirección de correo del remitente
            $mail->addAddress($correo, $nombre); // Utiliza el correo del usuario registrado
            $mail->Subject = 'Te has registrado correctamente';
            $mail->Body    = '
                                <html>
                                <body style="font-family: Arial, sans-serif; color: #333;">
                                    <div style="text-align: center; padding: 20px;">
                                        <h1 style="color: #FF0000;">Bienvenido a Arsenal Gym Bosque, ' . htmlspecialchars($nombre) . '!</h1>
                                        <p>¡Nos alegra que te hayas registrado! Ahora puedes iniciar sesión y disfrutar de todos nuestros servicios.</p>
                                        <p>Gracias por unirte a la comunidad de Arsenal Gym Bosque.</p>
                                    </div>
                                </body>
                                </html>
                            ';
            $mail->AltBody = 'Gracias por registrarte.';
    
            // Enviar el correo
            $mail->send();
            echo 'El correo ha sido enviado a ' . $correo;
        } catch (Exception $e) {
            echo "Error al enviar el correo: {$mail->ErrorInfo}";
        }
    }
}