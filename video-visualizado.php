<?php
// Obtener los datos POST
$data = json_decode(file_get_contents('php://input'), true);

// Datos de configuración de la base de datos
$config_db = array(
    "host" => "localhost",
    "port" => "3306",
    "user" => "root",
    "password" => "Aa123456@",
    "database" => "example"
);

// Establecer la conexión a la base de datos
$mysqli = new mysqli($config_db["host"], $config_db["user"], $config_db["password"], $config_db["database"]);

// Verificar si hay errores de conexión
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

// Consulta SQL que deseas ejecutar
$sql = "UPDATE video_yt_id SET active=0 WHERE video_id='". $data['video_yt_id'] ."'"; // Reemplaza 'video_yt_id' con el nombre de tu tabla y ajusta la consulta según tus necesidades.

// Ejecutar la consulta SQL
$resultado = $mysqli->query($sql);


// Procesar los datos o realizar cualquier otra operación que necesites
$respuesta_exito = array(
    "mensaje" => "Finalizado con exito!"
);

$respuesta_error = array(
    "mensaje" => "Error!"
);

// Enviar una respuesta JSON al frontend
header('Content-Type: application/json');

// Verificar si la consulta fue exitosa
if ($resultado) {
    echo json_encode($respuesta_exito);
}else {
    echo json_encode($respuesta_error);
}

?>
