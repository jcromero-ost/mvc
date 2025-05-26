<?php
$to = "isegura@osttech.es";
$subject = "Prueba correo PHP";
$message = "Este es un mensaje de prueba enviado desde PHP.";

// Cabeceras bien formateadas
$headers = "From: intranet@osttech.es\r\n";
$headers .= "Reply-To: intranet@osttech.es\r\n";  // Corregido, antes tenías "mail.os" que no es válido
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

// El parámetro -f indica el remitente para el envelope sender
if(mail($to, $subject, $message, $headers, "-f intranet@osttech.es")){
    echo "Correo enviado correctamente.";
} else {
    echo "Error al enviar correo.";
}
?>

