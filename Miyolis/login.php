<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conectar a Oracle (ajusta las credenciales según tus necesidades)
    $conexion = oci_connect('system', 'huracan0311', 'localhost/xe');

    // Verificar la conexión
    if (!$conexion) {
        $m = oci_error();
        die("Conexión fallida: " . htmlentities($m['message']));
    }

    $email = $_POST["email"];
    $clave = $_POST["clave"];

    // Verificar si los campos están vacíos
    if (empty($email) || empty($clave)) {
        $_SESSION["error_message"] = "Ambos campos son obligatorios";
        $_SESSION["error_display"] = "block";
        header("Location: inicio.php");
        exit();  // Termina la ejecución del script después de la redirección
    }

    // Consulta para verificar las credenciales
    $consulta = "SELECT id, nombre FROM usuarios WHERE email = :email AND clave = :clave";
    $stid = oci_parse($conexion, $consulta);
    
    oci_bind_by_name($stid, ':email', $email);
    oci_bind_by_name($stid, ':clave', $clave);

    oci_execute($stid);

    if ($row = oci_fetch_assoc($stid)) {
        // Inicio de sesión exitoso
        $_SESSION["id_usuario"] = $row["ID"];
        $_SESSION["nombre_usuario"] = $row["NOMBRE"];
        header("Location: Index.php"); // Redirige al panel de control
    } else {
        // Error en las credenciales
        $_SESSION["error_message"] = "Correo o contraseña incorrectos";
        $_SESSION["error_display"] = "block";
        header("Location: inicio.php");
    }

    oci_free_statement($stid);
    oci_close($conexion);
}

?>
