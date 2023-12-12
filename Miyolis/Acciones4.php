<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="tablas.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STOCK</title>
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
    <h1>STOCK</h1>
    <button type="submit" name="principal" id="principal"><a href="Stock.html">Volver </a></button>
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
        $idStock = isset($_POST['ID_STOCK']) ? $_POST['ID_STOCK'] : null;

        if (isset($_POST['insertar'])) {
            // Realizar la inserción
            $idArticulo = $_POST['ID_ARTICULO']; 
            $stock = $_POST['Stock'];

            $insertar = "INSERT INTO stock (ID_STOCK, ID_ARTICULO, STOCK)
                         VALUES ('$idStock', '$idArticulo', '$stock')";
            $stid = oci_parse($conexion, $insertar);
            oci_execute($stid);
            $mensaje = 'Inserción realizada con éxito.';

            // Consultar y mostrar los resultados actualizados
            $consulta = "SELECT ID_STOCK, ID_ARTICULO, STOCK FROM STOCK WHERE ID_STOCK= '$idStock'";
            $stid = oci_parse($conexion, $consulta);
            oci_execute($stid);
        } elseif (isset($_POST['eliminar'])) {
            // Realizar la eliminación
            $eliminar = "DELETE FROM stock WHERE ID_STOCK = '$idStock'";
            $stid = oci_parse($conexion, $eliminar);
            oci_execute($stid);
            $mensaje = 'Eliminación realizada con éxito.';

            // Consultar todos los registros después de la eliminación
            $consulta = 'SELECT ID_STOCK, ID_ARTICULO, STOCK FROM STOCK ORDER BY ID_STOCK';
            $stid = oci_parse($conexion, $consulta);
            oci_execute($stid);
        } elseif (isset($_POST['actualizar'])) {
            // Realizar la actualización
            $idArticulo = $_POST['ID_ARTICULO'];
            $stock = $_POST['Stock'];

            $actualizar = "UPDATE stock
                           SET ID_ARTICULO = '$idArticulo', STOCK = '$stock'
                           WHERE ID_STOCK = '$idStock'";
            $stid = oci_parse($conexion, $actualizar);
            oci_execute($stid);
            $mensaje = 'Actualización realizada con éxito.';

            // Consultar y mostrar los resultados actualizados
            $consulta = "SELECT ID_STOCK, ID_ARTICULO, STOCK FROM STOCK WHERE ID_STOCK = '$idStock'";
            $stid = oci_parse($conexion, $consulta);
            oci_execute($stid);
        } elseif (isset($_POST['buscar'])) {
            // Si es una búsqueda, consulta todos los registros o uno específico
            if ($idStock !== null) {
                $consulta = "SELECT ID_STOCK, ID_ARTICULO, STOCK FROM STOCK WHERE ID_STOCK = '$idStock'";
            } else {
                $consulta = 'SELECT ID_STOCK, ID_ARTICULO, STOCK FROM STOCK ORDER BY ID_STOCK';
            }

            $stid = oci_parse($conexion, $consulta);
            oci_execute($stid);
        } elseif (isset($_POST['consultar'])) {
            // Consultar todos los registros
            $consulta = "SELECT ID_STOCK, ID_ARTICULO, STOCK FROM STOCK ORDER BY ID_STOCK";
            $stid = oci_parse($conexion, $consulta);
            oci_execute($stid);
        }
    } else {
        // Consultar todos los registros por defecto
        $consulta = 'SELECT ID_STOCK, ID_ARTICULO, STOCK FROM STOCK ORDER BY ID_STOCK';
        $stid = oci_parse($conexion, $consulta);
        oci_execute($stid);
    }
    ?>

    <!-- Mostrar el mensaje en una caja -->
    <div class="mensaje" id="mensaje"><?php echo $mensaje; ?></div>

    <!-- Mostrar los resultados en una tabla HTML -->
    <table border="1">
        <tr>
            <th>ID Stock</th>
            <th>ID Artículo</th>
            <th>Stock</th>
        </tr>

        <?php
        // Cambio en la verificación de filas
        while ($fila = oci_fetch_assoc($stid)) {
            echo '<tr>';
            echo '<td>' . $fila['ID_STOCK'] . '</td>';
            echo '<td>' . $fila['ID_ARTICULO'] . '</td>';
            echo '<td>' . $fila['STOCK'] . '</td>';
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
