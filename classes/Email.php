<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email
{

    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion()
    {
        // Crear el objeto de email
        $mail = new PHPMailer();
        // Configurar SMPT
        $mail->isSMTP();
        $mail->Host = "sandbox.smtp.mailtrap.io";
        $mail->SMTPAuth = true;
        $mail->Username = "da49f4e0f1fafe";
        $mail->Password = "3e139ba9b76275";
        $mail->SMTPSecure = "tls";
        $mail->Port = 587;

        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');
        $mail->Subject = 'Confirma tu cuenta';

        

        // Set HTML
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Has creado tu cuenta en App Salon, Solo debes confirmarla presionando en ele siguiente enlace</p>";
        $contenido .= "<p>Presiona aquí <a href='http://localhost:8000/confirmar-cuenta?token=" . $this->token . "'>Confirmar Cuenta</a> </p>";
        $contenido .= "<p> Si tu no solicitaste eta cuenta, puedes ignorar el mensaje </p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        // eNVIAR EL EMAIL

        $mail->send();
    }

    public function enviarInstrucciones(){
         // Crear el objeto de email
         $mail = new PHPMailer();
         // Configurar SMPT
         $mail->isSMTP();
         $mail->Host = "sandbox.smtp.mailtrap.io";
         $mail->SMTPAuth = true;
         $mail->Username = "da49f4e0f1fafe";
         $mail->Password = "3e139ba9b76275";
         $mail->SMTPSecure = "tls";
         $mail->Port = 587;
 
         $mail->setFrom('cuentas@appsalon.com');
         $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');
         $mail->Subject = 'Restablece tu password';
 
         
 
         // Set HTML
         $mail->isHTML(true);
         $mail->CharSet = 'UTF-8';
 
         $contenido = "<html>";
         $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Has Solicitado reestablecer tu password, sigue el siguiente enlace para hacerlo</p>";
         $contenido .= "<p>Presiona aquí <a href='http://localhost:8000/recuperar?token=" . $this->token . "'>Reestablecer password</a> </p>";
         $contenido .= "<p> Si tu no solicitaste eta cuenta, puedes ignorar el mensaje </p>";
         $contenido .= "</html>";
 
         $mail->Body = $contenido;
 
         // eNVIAR EL EMAIL
 
         $mail->send();
    }
}
