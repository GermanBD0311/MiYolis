<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="tablas.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CATEGORÍAS</title>
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
    <h1>CATEGORÍAS</h1>
    <button type="submit" name="principal" id="principal"><a href="Categoria.html">Volver </a></button>
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
        $idCategoria = isset($_POST['ID_CATEGORIA']) ? $_POST['ID_CATEGORIA'] : null;

        if (isset($_POST['insertar'])) {
            // Realizar la inserción
            $nombre = $_POST['Nombre']; 
            $publico = $_POST['Publico'];
            $marca = $_POST['Marca'];

            $insertar = "INSERT INTO categoria (ID_CATEGORIA, NOMBRE, PUBLICO, MARCA)
                         VALUES ('$idCategoria', '$nombre', '$publico', '$marca')";
            $stid = oci_parse($conexion, $insertar);
            oci_execute($stid);
            $mensaje = 'Inserción realizada con éxito.';

            // Consultar y mostrar los resultados actualizados
            $consulta = "SELECT ID_CATEGORIA, NOMBRE, PUBLICO, MARCA FROM CATEGORIA WHERE ID_CATEGORIA =' $idCategoria'";
            $stid = oci_parse($conexion, $consulta);
            oci_execute($stid);
        } elseif (isset($_POST['eliminar'])) {
            // Realizar la eliminación
            $eliminar = "DELETE FROM categoria WHERE ID_CATEGORIA = '$idCategoria'";
            $stid = oci_parse($conexion, $eliminar);
            oci_execute($stid);
            $mensaje = 'Eliminación realizada con éxito.';

            // Consultar todas las categorías después de la eliminación
            $consulta = 'SELECT ID_CATEGORIA, NOMBRE, PUBLICO, MARCA FROM CATEGORIA ORDER BY ID_CATEGORIA';
            $stid = oci_parse($conexion, $consulta);
            oci_execute($stid);
        } elseif (isset($_POST['actualizar'])) {
            // Realizar la actualización
            $nombre = $_POST['Nombre'];
            $publico = $_POST['Publico'];
            $marca = $_POST['Marca'];

            $actualizar = "UPDATE categoria
                           SET NOMBRE = '$nombre', PUBLICO = '$publico',
                               MARCA = '$marca'
                           WHERE ID_CATEGORIA = '$idCategoria'";
            $stid = oci_parse($conexion, $actualizar);
            oci_execute($stid);
            $mensaje = 'Actualización realizada con éxito.';

            // Consultar y mostrar los resultados actualizados
            $consulta = "SELECT ID_CATEGORIA, NOMBRE, PUBLICO, MARCA FROM CATEGORIA WHERE ID_CATEGORIA = '$idCategoria'";
            $stid = oci_parse($conexion, $consulta);
            oci_execute($stid);
        } elseif (isset($_POST['buscar'])) {
            // Si es una búsqueda, consulta todas las categorías o una específica
            if ($idCategoria !== null) {
                $consulta = "SELECT ID_CATEGORIA, NOMBRE, PUBLICO, MARCA FROM CATEGORIA WHERE ID_CATEGORIA = '$idCategoria'";
            } else {
                $consulta = 'SELECT ID_CATEGORIA, NOMBRE, PUBLICO, MARCA FROM CATEGORIA ORDER BY ID_CATEGORIA';
            }

            $stid = oci_parse($conexion, $consulta);
            oci_execute($stid);
        } elseif (isset ($_POST ['consultar'])) {
            // Consultar todas las categorías
            $consulta = "SELECT ID_CATEGORIA, NOMBRE, PUBLICO, MARCA FROM CATEGORIA ORDER BY ID_CATEGORIA";
            $stid = oci_parse($conexion, $consulta);
            oci_execute($stid);
        }
    } else {
        // Consultar todas las categorías por defecto
        $consulta = 'SELECT ID_CATEGORIA, NOMBRE, PUBLICO, MARCA FROM CATEGORIA ORDER BY ID_CATEGORIA';
        $stid = oci_parse($conexion, $consulta);
        oci_execute($stid);
    }
    ?>

    <!-- Mostrar el mensaje en una caja -->
    <div class="mensaje" id="mensaje"><?php echo $mensaje; ?></div>

    <!-- Mostrar los resultados en una tabla HTML -->
    <table border="1">
        <tr>
            <th>ID Categoría</th>
            <th>Nombre</th>
            <th>Público</th>
            <th>Marca</th>
        </tr>

        <?php
        // Cambio en la verificación de filas
        while ($fila = oci_fetch_assoc($stid)) {
            echo '<tr>';
            echo '<td>' . $fila['ID_CATEGORIA'] . '</td>';
            echo '<td>' . $fila['NOMBRE'] . '</td>';
            echo '<td>' . $fila['PUBLICO'] . '</td>';
            echo '<td>' . $fila['MARCA'] . '</td>';
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
