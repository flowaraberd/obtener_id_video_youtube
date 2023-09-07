<?php
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
$sql = "SELECT * FROM video_yt_id WHERE active=1"; // Reemplaza 'video_yt_id' con el nombre de tu tabla y ajusta la consulta según tus necesidades.

// Ejecutar la consulta SQL
$resultado = $mysqli->query($sql);


// Verificar si la consulta fue exitosa
if ($resultado) {
    // Almacenar los resultados en una variable
    $datos = array();
    while ($fila = $resultado->fetch_assoc()) {
        $datos[] = $fila;
    echo (
        "<div> 
            <input type='text' class='". $fila['video_id'] ."' value='https://www.youtube.com/' data-url-video='". $fila['video_id'] ."' readonly/>". 
            "<input type='button' data-is-btn='true' id='". $fila['video_id'] . "' data-copy-url='true' data-id-video='". $fila['video_id'] ."' value='Copiar video'> 
            <input type='text' class='". $fila['video_id'] ."' value='". $fila['video_id'] ."' readonly/>". 
            "<input type='button' data-is-btn='true' id='". $fila['video_id'] . "' data-copy-string='true' data-id-video='". $fila['video_id'] ."' value='Copiar String'>
            <br/>
            <input type='button' id='btn_finish' data-id-video='". $fila['video_id'] ."' value='Finalizado'> </div>" 
    );
    }
    if ($resultado->num_rows <= 0){
        echo "<strong>No se ha encontrado más videos en este momento, por favor intentarlo más tarde.</strong>";
    }
        
    // Ahora $datos contiene los resultados de la consulta
    // Puedes trabajar con los datos aquí

    // Cerrar la conexión a la base de datos cuando hayas terminado
    $mysqli->close();
} else {
    // Si la consulta falla, mostrar un mensaje de error
    echo "Error en la consulta: " . $mysqli->error;
}

echo '<script>
document.addEventListener("click", (event)=>{
    if (event.target.getAttribute("data-is-btn") == "true"){
    let textoCopiar = event.target.getAttribute("data-id-video");
    let videoYT = "https://www.youtube.com/watch?v=" + event.target.getAttribute("data-id-video");

    if (event.target.getAttribute("data-copy-url") == "true"){
        navigator.clipboard.writeText(videoYT)
                    .then(function() {
                        // Éxito: el valor se copió al portapapeles
                    })
                    .catch(function(error) {
                        // Manejar el error si no se puede copiar al portapapeles
                        alert("No se pudo copiar al portapapeles: " + error);
                    });
    }
    if (event.target.getAttribute("data-copy-string") == "true"){
        navigator.clipboard.writeText(event.target.getAttribute("data-id-video"))
                    .then(function() {
                        // Éxito: el valor se copió al portapapeles
                    })
                    .catch(function(error) {
                        // Manejar el error si no se puede copiar al portapapeles
                        alert("No se pudo copiar al portapapeles: " + error);
                    });
                    
                }
                
                
            }
            if (event.target.id == "btn_finish"){
                fetch("video-visualizado.php", {
                    method: "POST",
                    body: JSON.stringify({ video_yt_id: event.target.getAttribute("data-id-video") }), // Puedes enviar datos aquí
                    headers: {
                    "Content-Type": "application/json"
                    }
                    })
                    .catch(function(error) {
                        // Manejar el error si no se puede copiar al portapapeles
                        alert("No se pudo enviar la solicitud: " + error);
                    })
                    .finally(()=>{
                        window.location.reload();
                    });
                    
                
            }
});
</script>';
?>
