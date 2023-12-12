<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="tablas.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PROVEEDORES</title>
    <style>
        .mensaje {
            background-color: #ff66b2;
            color: #0d0808; 
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            display: none;
        }
    </style>
</head>
<header>
    <img id="logo" src="logotablas.jpeg" alt="Logotipo de la empresa">
    <h1>PROVEEDORES</h1>
    <button type="submit" name="principal" id="principal"><a href="Proveedor.html">Volver </a></button>
</header>
<section class="section1">
<body>
    <?php
    $mensaje = ''; 
    $conexion = oci_connect('system', 'huracan0311', 'localhost/xe');

    if (!$conexion) {
        $m = oci_error();
        trigger_error(htmlentities($m['message'], ENT_QUOTES), E_USER_ERROR);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $idProveedor = isset($_POST['ID_PROVEEDOR']) ? $_POST['ID_PROVEEDOR'] : null;

        if (isset($_POST['insertar'])) {
            // Realizar la inserción
            $idArticulo = $_POST['ID_ARTICULO']; 
            $nombre = $_POST['Nombre'];
            $telefono = $_POST['Telefono'];
            $numCuenta1 = $_POST['NUM_CUENTA1'];
            $numCuenta2 = $_POST['NUM_CUENTA2'];

            $insertar = "INSERT INTO proveedor (ID_PROVEEDOR, ID_ARTICULO, Nombre,Telefono, NUM_CUENTA1, NUM_CUENTA2)
                         VALUES ('$idProveedor', '$idArticulo', '$nombre', '$telefono', '$numCuenta1', '$numCuenta2')";
            $stid = oci_parse($conexion, $insertar);
            oci_execute($stid);
            $mensaje = 'Inserción realizada con éxito.';

            // Consultar y mostrar los resultados actualizados
            $consulta = "SELECT ID_PROVEEDOR, ID_ARTICULO, NOMBRE, TELEFONO, NUM_CUENTA1, NUM_CUENTA2 FROM PROVEEDOR WHERE ID_PROVEEDOR = '$idProveedor'";
            $stid = oci_parse($conexion, $consulta);
            oci_execute($stid);
        } elseif (isset($_POST['eliminar'])) {
            // Realizar la eliminación
            $eliminar = "DELETE FROM proveedor WHERE ID_PROVEEDOR = '$idProveedor'";
            $stid = oci_parse($conexion, $eliminar);
            oci_execute($stid);
            $mensaje = 'Eliminación realizada con éxito.';

            // Consultar todos los proveedores después de la eliminación
            $consulta = 'SELECT ID_PROVEEDOR, ID_ARTICULO, NOMBRE, TELEFONO, NUM_CUENTA1, NUM_CUENTA2 FROM PROVEEDOR ORDER BY ID_PROVEEDOR';
            $stid = oci_parse($conexion, $consulta);
            oci_execute($stid);
        } elseif (isset($_POST['actualizar'])) {
            // Realizar la actualización
            $idArticulo = $_POST['ID_ARTICULO'];
            $nombre = $_POST['Nombre'];
            $telefono = $_POST['Telefono'];
            $numCuenta1 = $_POST['NUM_CUENTA1'];
            $numCuenta2 = $_POST['NUM_CUENTA2'];

            $actualizar = "UPDATE proveedor
                           SET ID_ARTICULO = '$idArticulo', NOMBRE = '$nombre',
                               TELEFONO = '$telefono', NUM_CUENTA1 = '$numCuenta1', NUM_CUENTA2 = '$numCuenta2'
                           WHERE ID_PROVEEDOR = '$idProveedor'";
            $stid = oci_parse($conexion, $actualizar);
            oci_execute($stid);
            $mensaje = 'Actualización realizada con éxito.';

            // Consultar y mostrar los resultados actualizados
            $consulta = "SELECT ID_PROVEEDOR, ID_ARTICULO, NOMBRE, TELEFONO, NUM_CUENTA1, NUM_CUENTA2 FROM PROVEEDOR WHERE ID_PROVEEDOR = '$idProveedor'";
            $stid = oci_parse($conexion, $consulta);
            oci_execute($stid);
        } elseif (isset($_POST['buscar'])) {
            // Si es una búsqueda, consulta todos los proveedores o uno específico
            if ($idProveedor !== null) {
                $consulta = "SELECT ID_PROVEEDOR, ID_ARTICULO, NOMBRE, TELEFONO, NUM_CUENTA1, NUM_CUENTA2 FROM PROVEEDOR WHERE ID_PROVEEDOR = '$idProveedor'";
            } else {
                $consulta = 'SELECT ID_PROVEEDOR, ID_ARTICULO, NOMBRE, TELEFONO, NUM_CUENTA1, NUM_CUENTA2 FROM PROVEEDOR ORDER BY ID_PROVEEDOR';
            }

            $stid = oci_parse($conexion, $consulta);
            oci_execute($stid);
        } elseif (isset ($_POST ['consultar'])) {
            // Consultar todos los proveedores
            $consulta = "SELECT ID_PROVEEDOR, ID_ARTICULO, NOMBRE, TELEFONO, NUM_CUENTA1, NUM_CUENTA2 FROM PROVEEDOR ORDER BY ID_PROVEEDOR";
            $stid = oci_parse($conexion, $consulta);
            oci_execute($stid);
        }
    } else {
        // Consultar todos los proveedores por defecto
        $consulta = 'SELECT ID_PROVEEDOR, ID_ARTICULO, NOMBRE, TELEFONO, NUM_CUENTA1, NUM_CUENTA2 FROM PROVEEDOR ORDER BY ID_PROVEEDOR';
        $stid = oci_parse($conexion, $consulta);
        oci_execute($stid);
    }
    ?>

    <!-- Mostrar el mensaje en una caja -->
    <div class="mensaje" id="mensaje"><?php echo $mensaje; ?></div>

    <!-- Mostrar los resultados en una tabla HTML -->
    <table border="1">
        <tr>
            <th>ID Proveedor</th>
            <th>ID Artículo</th>
            <th>Nombre</th>
            <th>Teléfono</th>
            <th>Número de Cuenta 1</th>
            <th>Número de Cuenta 2</th>
        </tr>

        <?php
        // Cambio en la verificación de filas
        while ($fila = oci_fetch_assoc($stid)) {
            echo '<tr>';
            echo '<td>' . $fila['ID_PROVEEDOR'] . '</td>';
            echo '<td>' . $fila['ID_ARTICULO'] . '</td>';
            echo '<td>' . $fila['NOMBRE'] . '</td>';
            echo '<td>' . $fila['TELEFONO'] . '</td>';
            echo '<td>' . $fila['NUM_CUENTA1'] . '</td>';
            echo '<td>' . $fila['NUM_CUENTA2'] . '</td>';
            echo '</tr>';
        }
        ?>
    </table>

    <?php
    oci_free_statement($stid);
    oci_close($conexion);
    ?>

    <script>
        // Mostrar el mensaje por 2 segundos y luego ocultarlo
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
