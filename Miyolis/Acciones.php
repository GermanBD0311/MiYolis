<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="tablas.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CLIENTES</title>
    <style>
        .mensaje {
            background-color: #ff66b2; /* Color rosa claro */
            color: #0d0808; /* Texto blanco */
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            display: none;
        }

    </style>
</head>
<header>
    <img id="logo" src="logotablas.jpeg" alt="Logotipo de la empresa">
    <h1>CLIENTES</h1>
    <button type="submit" name="principal" id="principal"><a href="Clientes.html">Volver </a></button>
</header>
<section class="section1">
<body>
    <?php
    $mensaje = ''; // Inicializar el mensaje

    $conexion = oci_connect('system', 'huracan0311', 'localhost/xe');

    if (!$conexion) {
        $m = oci_error();
        trigger_error(htmlentities($m['message'], ENT_QUOTES), E_USER_ERROR);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $idCliente = isset($_POST['ID_CLIENTE']) ? $_POST['ID_CLIENTE'] : null;

        if (isset($_POST['insertar'])) {
            // Realizar la inserción
            $nombre = $_POST['NOMBRE']; // Ajustar según tus campos
            $apellidoP = $_POST['APELLIDO_P'];
            $apellidoM = $_POST['APELLIDO_M'];
            $telefono = $_POST['TELEFONO'];

            $insertar = "INSERT INTO clientes (ID_CLIENTE, NOMBRE, APELLIDO_P, APELLIDO_M, TELEFONO)
                         VALUES ('$idCliente', '$nombre', '$apellidoP', '$apellidoM', '$telefono')";
            $stid = oci_parse($conexion, $insertar);
            oci_execute($stid);
            $mensaje = 'Inserción realizada con éxito.';
        } elseif (isset($_POST['eliminar'])) {
            // Realizar la eliminación
            $eliminar = "DELETE FROM clientes WHERE ID_CLIENTE = '$idCliente'";
            $stid = oci_parse($conexion, $eliminar);
            oci_execute($stid);
            $mensaje = 'Eliminación realizada con éxito.';
        } elseif (isset($_POST['actualizar'])) {
            // Realizar la actualización
            // Ajusta según tus campos para actualizar
            $nombre = $_POST['NOMBRE'];
            $apellidoP = $_POST['APELLIDO_P'];
            $apellidoM = $_POST['APELLIDO_M'];
            $telefono = $_POST['TELEFONO'];

            $actualizar = "UPDATE clientes
                           SET NOMBRE = '$nombre', APELLIDO_P = '$apellidoP',
                               APELLIDO_M = '$apellidoM', TELEFONO = '$telefono'
                           WHERE ID_CLIENTE = '$idCliente'";
            $stid = oci_parse($conexion, $actualizar);
            oci_execute($stid);
            $mensaje = 'Actualización realizada con éxito.';
        }
    }

    // Realizar la consulta después de realizar la acción
    $consulta = 'SELECT ID_CLIENTE, NOMBRE, APELLIDO_P, APELLIDO_M, TELEFONO FROM CLIENTES ORDER BY ID_CLIENTE';
    $stid = oci_parse($conexion, $consulta);
    oci_execute($stid);
    ?>

    <!-- Mostrar el mensaje en una caja -->
    <div class="mensaje" id="mensaje"><?php echo $mensaje; ?></div>

    <!-- Mostrar los resultados en una tabla HTML -->
    <table border="1">
        <tr>
            <th>ID Cliente</th>
            <th>Nombre</th>
            <th>Apellido Paterno</th>
            <th>Apellido Materno</th>
            <th>Teléfono</th>
        </tr>

        <?php
        while ($fila = oci_fetch_assoc($stid)) {
            echo '<tr>';
            echo '<td>' . $fila['ID_CLIENTE'] . '</td>';
            echo '<td>' . $fila['NOMBRE'] . '</td>';
            echo '<td>' . $fila['APELLIDO_P'] . '</td>';
            echo '<td>' . $fila['APELLIDO_M'] . '</td>';
            echo '<td>' . $fila['TELEFONO'] . '</td>';
            echo '</tr>';
        }
        ?>
    </table>

    <?php
    oci_free_statement($stid);
    oci_close($conexion);
    ?>

    <script>
        // Mostrar el mensaje por 1 segundo y luego ocultarlo
        document.addEventListener('DOMContentLoaded', function () {
            var mensaje = document.getElementById('mensaje');
            if (mensaje.innerHTML !== '') {
                mensaje.style.display = 'block';
                setTimeout(function () {
                    mensaje.style.display = 'none';
                }, 2000);
            }
        });
    </script>
</body>
</section>
</html>
                 
   
