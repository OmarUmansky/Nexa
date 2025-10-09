<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:\xampp\htdocs\Proyecto-de-egreso-main\PHPMailer\src\Exception.php';
require 'C:\xampp\htdocs\Proyecto-de-egreso-main\PHPMailer\src\PHPMailer.php';
require 'C:\xampp\htdocs\Proyecto-de-egreso-main\PHPMailer\src\SMTP.php';

function enviarConfirmacionCita($email_usuario, $nombre_usuario, $fecha_cita, $hora_cita, $servicio = 'Servicio seleccionado') {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'mylbarberiaypeluqueria@gmail.com';
        $mail->Password = 'ngyc xjih vapg rogz';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true,
    )
);


        $mail->setFrom('tu_correo@gmail.com', 'M&L Barberia y Peluqueria');
        $mail->addAddress($email_usuario, $nombre_usuario);
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Confirmación de tu cita – M&L Barbería y Peluquería';
        // Embed brand logo (used inside HTML with cid:brand_logo)
        $mail->addEmbeddedImage('C:\\xampp\\htdocs\\Proyecto-de-egreso-main\\assets\\fondou.png', 'brand_logo', 'fondou.png');
        $mail->Body = "
            <div style=\"font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif; background:#f6f7fb; padding:24px; color:#0f172a;\">
              <div style=\"max-width:560px; margin:0 auto; background:#ffffff; border-radius:14px; overflow:hidden; box-shadow:0 8px 28px rgba(2,6,23,.12); border:1px solid #eef2f7;\">
                <div style=\"text-align:center; padding:22px 20px 8px;\">
                  <img src=\"cid:brand_logo\" alt=\"M&L Barbería y Peluquería\" style=\"width:96px; height:auto; border-radius:12px;\" />
                </div>
                <div style=\"text-align:center; padding:4px 24px 0;\">
                  <h2 style=\"margin:0; font-size:20px; color:#111827;\">Cita confirmada</h2>
                  <p style=\"margin:6px 0 0; font-size:13px; color:#6b7280;\">M&amp;L Barbería y Peluquería</p>
                </div>
                <div style=\"padding:22px 24px;\">
                  <p style=\"margin:0 0 14px; font-size:15px;\">Hola <strong>$nombre_usuario</strong>, tu reserva fue confirmada.</p>
                  <div style=\"background:#f8fafc; border:1px solid #e5e7eb; border-radius:10px; padding:14px 16px;\">
                    <p style=\"margin:0;\"><strong>Fecha:</strong> $fecha_cita</p>
                    <p style=\"margin:8px 0 0;\"><strong>Hora:</strong> $hora_cita</p>
                    <p style=\"margin:8px 0 0;\"><strong>Servicio:</strong> $servicio</p>
                  </div>
                  <p style=\"margin:14px 0 0; font-size:13px; color:#6b7280;\">Llega 5 minutos antes. Para reprogramar o cancelar, responde este correo con 3 horas de anticipación.</p>
                </div>
                <div style=\"padding:16px 24px 22px; text-align:center;\">
                  <a href=\"#\" style=\"display:inline-block; background:#111827; color:#ffffff; text-decoration:none; padding:10px 16px; border-radius:8px; font-weight:600;\">Agregar a mi calendario</a>
                </div>
              </div>
            </div>
        ";
        $mail->AltBody = "Hola $nombre_usuario,\n\nTu cita ha sido confirmada.\n\nFecha: $fecha_cita\nHora: $hora_cita\nServicio: $servicio\n\nSi necesitas reprogramar o cancelar, responde este correo.\n\nM&L Barbería y Peluquería";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return "Error al enviar el correo: {$mail->ErrorInfo}";
    }
}
?>
