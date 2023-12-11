<?php
session_start();

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conectar a la base de datos (cambiar con tus propias credenciales)
    $conexion = oci_connect('system', 'huracan0311', 'localhost/xe');

    if (!$conexion) {
        $m = oci_error();
        trigger_error(htmlentities($m['message'], ENT_QUOTES), E_USER_ERROR);
    }

    // Obtener el correo electrónico proporcionado por el usuario
    $email = isset($_POST["email"]) ? $_POST["email"] : "";

    // Verificar si el correo electrónico existe en la base de datos
    $consulta = "SELECT ID, NOMBRE, clave FROM usuarios WHERE email = :email";
    $stid = oci_parse($conexion, $consulta);
    oci_bind_by_name($stid, ":email", $email);
    oci_execute($stid);

    if ($fila = oci_fetch_assoc($stid)) {
        // Enviar la contraseña al correo electrónico
        $destinatario = $email;
        $asunto = "Recuperación de Contraseña";
        $mensaje = "Hola " . $fila["NOMBRE"] . ",\n\nTu contraseña es: " . $fila["clave"];
        $headers = "From: germanbecerril06@gmail.com";

        // Envía el correo
        mail($destinatario, $asunto, $mensaje, $headers);

        // Redirige al usuario a una página de éxito
        header("Location: recuperar_exitoso.html");
        exit();
    } else {
        // El correo electrónico no existe en la base de datos
        $mensaje_error = "El correo electrónico proporcionado no está registrado.";
    }

    oci_free_statement($stid);
    oci_close($conexion);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
</head>
<body>
    <h2>Recuperar Contraseña</h2>

    <?php if (isset($mensaje_error)) : ?>
        <p style="color: red;"><?php echo $mensaje_error; ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <label for="email">Correo Electrónico:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <button type="submit">Recuperar Contraseña</button>
    </form>
</body>
</html>

