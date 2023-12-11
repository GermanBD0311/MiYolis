<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conectar a Oracle (ajusta las credenciales según tus necesidades)
    $conexion = oci_connect('system', 'huracan0311', 'localhost/xe');

    // Verificar la conexión
    if (!$conexion) {
        $m = oci_error();
        die("Conexión fallida: " . htmlentities($m['message']));
    }

    $nombre = $_POST["nombre"];
    $email = $_POST["email"];
    $contrasena = $_POST["contrasena"];

    // Verificar si el correo ya está registrado
    $consulta_correo_existente = "SELECT COUNT(*) AS total FROM usuarios WHERE email = :email";
    $stid_correo_existente = oci_parse($conexion, $consulta_correo_existente);
    oci_bind_by_name($stid_correo_existente, ':email', $email);
    oci_execute($stid_correo_existente);
    $row_correo_existente = oci_fetch_assoc($stid_correo_existente);

    if ($row_correo_existente['TOTAL'] > 0) {
        echo "El correo ya está registrado. Por favor, elige otro.";
    } else {
        // Insertar nuevo usuario
        $consulta_insertar = "INSERT INTO usuarios (nombre, email, contrasena) VALUES (:nombre, :email, :contrasena)";
        $stid_insertar = oci_parse($conexion, $consulta_insertar);
        oci_bind_by_name($stid_insertar, ':nombre', $nombre);
        oci_bind_by_name($stid_insertar, ':email', $email);
        oci_bind_by_name($stid_insertar, ':contrasena', $contrasena);

        oci_execute($stid_insertar);

        echo "Registro exitoso. Ahora puedes iniciar sesión.";
    }

    oci_free_statement($stid_correo_existente);
    oci_free_statement($stid_insertar);
    oci_close($conexion);
}

?>
 