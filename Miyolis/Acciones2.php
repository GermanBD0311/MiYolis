<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="tablas.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARTÍCULOS</title>
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
    <h1>ARTÍCULOS</h1>
    <button type="submit" name="principal" id="principal"><a href="Articulo.html">Volver </a></button>
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
        $idArticulo = isset($_POST['ID_ARTICULO']) ? $_POST['ID_ARTICULO'] : null;

        if (isset($_POST['insertar'])) {
            // Realizar la inserción
            $idCategoria = $_POST['ID_CATEGORIA']; 
            $precio = $_POST['PRECIO'];
            $nombre = $_POST['NOMBRE'];

            $insertar = "INSERT INTO articulo (ID_ARTICULO, ID_CATEGORIA, PRECIO, NOMBRE)
                         VALUES ('$idArticulo', '$idCategoria', '$precio', '$nombre')";
            $stid = oci_parse($conexion, $insertar);
            oci_execute($stid);
            $mensaje = 'Inserción realizada con éxito.';

            // Consultar y mostrar los resultados actualizados
            $consulta = 'SELECT ID_ARTICULO, ID_CATEGORIA, PRECIO, NOMBRE FROM ARTICULO ORDER BY ID_ARTICULO';
            $stid = oci_parse($conexion, $consulta);
            oci_execute($stid);
        } elseif (isset($_POST['eliminar'])) {
            // Realizar la eliminación
            $eliminar = "DELETE FROM articulo WHERE ID_ARTICULO = '$idArticulo'";
            $stid = oci_parse($conexion, $eliminar);
            oci_execute($stid);
            $mensaje = 'Eliminación realizada con éxito.';

            // Consultar todos los artículos después de la eliminación
            $consulta = 'SELECT ID_ARTICULO, ID_CATEGORIA, PRECIO, NOMBRE FROM ARTICULO ORDER BY ID_ARTICULO';
            $stid = oci_parse($conexion, $consulta);
            oci_execute($stid);
        } elseif (isset($_POST['actualizar'])) {
            // Realizar la actualización
            $idCategoria = $_POST['ID_CATEGORIA'];
            $precio = $_POST['PRECIO'];
            $nombre = $_POST['NOMBRE'];

            $actualizar = "UPDATE articulo
                           SET ID_CATEGORIA = '$idCategoria', PRECIO = '$precio',
                               NOMBRE = '$nombre'
                           WHERE ID_ARTICULO = '$idArticulo'";
            $stid = oci_parse($conexion, $actualizar);
            oci_execute($stid);
            $mensaje = 'Actualización realizada con éxito.';

            // Consultar y mostrar los resultados actualizados
            $consulta = "SELECT ID_ARTICULO, ID_CATEGORIA, PRECIO, NOMBRE FROM ARTICULO WHERE ID_ARTICULO = '$idArticulo'";
            $stid = oci_parse($conexion, $consulta);
            oci_execute($stid);
        } elseif (isset($_POST['buscar'])) {
            // Si es una búsqueda, consulta todos los artículos o uno específico
            if ($idArticulo !== null) {
                $consulta = "SELECT ID_ARTICULO, ID_CATEGORIA, PRECIO, NOMBRE FROM ARTICULO WHERE ID_ARTICULO = '$idArticulo'";
            } else {
                $consulta = 'SELECT ID_ARTICULO, ID_CATEGORIA, PRECIO, NOMBRE FROM ARTICULO ORDER BY ID_ARTICULO';
            }

            $stid = oci_parse($conexion, $consulta);
            oci_execute($stid);
        } elseif (isset ($_POST ['consultar'])) {
            // Consultar todos los artículos
            $consulta = "SELECT ID_ARTICULO, ID_CATEGORIA, PRECIO, NOMBRE FROM ARTICULO ORDER BY ID_ARTICULO";
            $stid = oci_parse($conexion, $consulta);
            oci_execute($stid);
        }
    } else {
        // Consultar todos los artículos por defecto
        $consulta = 'SELECT ID_ARTICULO, ID_CATEGORIA, PRECIO, NOMBRE FROM ARTICULO ORDER BY ID_ARTICULO';
        $stid = oci_parse($conexion, $consulta);
        oci_execute($stid);
    }
    ?>

    <!-- Mostrar el mensaje en una caja -->
    <div class="mensaje" id="mensaje"><?php echo $mensaje; ?></div>

    <!-- Mostrar los resultados en una tabla HTML -->
    <table border="1">
        <tr>
            <th>ID Artículo</th>
            <th>ID Categoría</th>
            <th>Precio</th>
            <th>Nombre</th>
        </tr>

        <?php
        // Cambio en la verificación de filas
        while ($fila = oci_fetch_assoc($stid)) {
            echo '<tr>';
            echo '<td>' . $fila['ID_ARTICULO'] . '</td>';
            echo '<td>' . $fila['ID_CATEGORIA'] . '</td>';
            echo '<td>' . $fila['PRECIO'] . '</td>';
            echo '<td>' . $fila['NOMBRE'] . '</td>';
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
