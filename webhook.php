<?php

$botToken = "7974078878:AAGIuvWrSQwjkrWhcwNSEqquvARRoiKA8YY";
$website = "https://api.telegram.org/bot".$botToken;

// Obtiene el contenido del mensaje
$input = file_get_contents("php://input");
$update = json_decode($input, TRUE);

// Verifica si el mensaje tiene texto
if(isset($update['message']['text'])) {
    $message = $update['message']['text'];
    $usuario = $update['message']['from']['first_name'];

    // Conexión a la base de datos
    $conn = new mysqli('localhost', 'root', '', 'denuncias_db');
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Inserta la denuncia en la base de datos
    $stmt = $conn->prepare("INSERT INTO denuncias (usuario, denuncia) VALUES (?, ?)");
    $stmt->bind_param("ss", $usuario, $message);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    // Responde al usuario
    $chatId = $update['message']['chat']['id'];
    $response = "Gracias por tu denuncia, hemos recibido tu mensaje.";
    file_get_contents($website."/sendMessage?chat_id=".$chatId."&text=".urlencode($response));
}

?>
