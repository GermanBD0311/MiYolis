<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="tablas.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>APARTADO</title>
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
    <h1>APARTADO</h1>
    <button type="submit" name="principal" id="principal"><a href="Apartado.html">Volver </a></button>
    
</header>
<div class="text-right mb-2">
    <a href="VISTA/PruebaV.php" target="_blank" class="btn btn-succes"><i class="fad fa-file-pdf"></i> Imprimir Apartado </a>
    </div>
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
        $idApartado = isset($_POST['ID_APARTADO']) ? $_POST['ID_APARTADO'] : null;

        if (isset($_POST['insertar'])) {
            // Realizar la inserción
            $idCliente = $_POST['ID_CLIENTE']; 
            $fecha = $_POST['FECHA'];
            $monto = $_POST['MONTO'];
            $idArticulo = $_POST['ID_ARTICULO'];

            $insertar = "INSERT INTO apartado (ID_APARTADO, ID_CLIENTE, FECHA, MONTO, ID_ARTICULO)
                         VALUES ('$idApartado', '$idCliente', '$fecha', '$monto', '$idArticulo')";
            $stid = oci_parse($conexion, $insertar);
            oci_execute($stid);
            $mensaje = 'Inserción realizada con éxito.';

            // Consultar y mostrar los resultados actualizados
            $consulta = 'SELECT ID_APARTADO, ID_CLIENTE, FECHA, MONTO, ID_ARTICULO FROM APARTADO ORDER BY ID_APARTADO';
            $stid = oci_parse($conexion, $consulta);
            oci_execute($stid);
        } elseif (isset($_POST['eliminar'])) {
            // Realizar la eliminación
            $eliminar = "DELETE FROM apartado WHERE ID_APARTADO = '$idApartado'";
            $stid = oci_parse($conexion, $eliminar);
            oci_execute($stid);
            $mensaje = 'Eliminación realizada con éxito.';

            // Consultar todos los registros después de la eliminación
            $consulta = 'SELECT ID_APARTADO, ID_CLIENTE, FECHA, MONTO, ID_ARTICULO FROM APARTADO ORDER BY ID_APARTADO';
            $stid = oci_parse($conexion, $consulta);
            oci_execute($stid);
        } elseif (isset($_POST['actualizar'])) {
            // Realizar la actualización
            $idCliente = $_POST['ID_CLIENTE'];
            $fecha = $_POST['FECHA'];
            $monto = $_POST['MONTO'];
            $idArticulo = $_POST['ID_ARTICULO'];

            $actualizar = "UPDATE apartado
                           SET ID_CLIENTE = '$idCliente', FECHA = '$fecha',
                               MONTO = '$monto', ID_ARTICULO = '$idArticulo'
                           WHERE ID_APARTADO = '$idApartado'";
            $stid = oci_parse($conexion, $actualizar);
            oci_execute($stid);
            $mensaje = 'Actualización realizada con éxito.';

            // Consultar y mostrar los resultados actualizados
            $consulta = "SELECT ID_APARTADO, ID_CLIENTE, FECHA, MONTO, ID_ARTICULO FROM APARTADO WHERE ID_APARTADO = '$idApartado'";
            $stid = oci_parse($conexion, $consulta);
            oci_execute($stid);
        } elseif (isset($_POST['buscar'])) {
            // Si es una búsqueda, consulta todos los registros o uno específico
            if ($idApartado !== null) {
                $consulta = "SELECT ID_APARTADO, ID_CLIENTE, FECHA, MONTO, ID_ARTICULO FROM APARTADO WHERE ID_APARTADO = '$idApartado'";
            } else {
                $consulta = 'SELECT ID_APARTADO, ID_CLIENTE, FECHA, MONTO, ID_ARTICULO FROM APARTADO ORDER BY ID_APARTADO';
            }

            $stid = oci_parse($conexion, $consulta);
            oci_execute($stid);
        } elseif (isset($_POST['consultar'])) {
            // Consultar todos los registros
            $consulta = "SELECT ID_APARTADO, ID_CLIENTE, FECHA, MONTO, ID_ARTICULO FROM APARTADO ORDER BY ID_APARTADO";
            $stid = oci_parse($conexion, $consulta);
            oci_execute($stid);
        }
    } else {
        // Consultar todos los registros por defecto
        $consulta = 'SELECT ID_APARTADO, ID_CLIENTE, FECHA, MONTO, ID_ARTICULO FROM APARTADO ORDER BY ID_APARTADO';
        $stid = oci_parse($conexion, $consulta);
        oci_execute($stid);
    }
    ?>

    <!-- Mostrar el mensaje en una caja -->
    <div class="mensaje" id="mensaje"><?php echo $mensaje; ?></div>

    <!-- Mostrar los resultados en una tabla HTML -->
    <table border="1">
        <tr>
            <th>ID Apartado</th>
            <th>ID Cliente</th>
            <th>Fecha</th>
            <th>Monto</th>
            <th>ID Artículo</th>
        </tr>

        <?php
        // Cambio en la verificación de filas
        while ($fila = oci_fetch_assoc($stid)) {
            if ($fila) {
                echo '<tr>';
                echo '<td>' . $fila['ID_APARTADO'] . '</td>';
                echo '<td>' . $fila['ID_CLIENTE'] . '</td>';
                echo '<td>' . $fila['FECHA'] . '</td>';
                echo '<td>' . $fila['MONTO'] . '</td>';
                echo '<td>' . $fila['ID_ARTICULO'] . '</td>';
                echo '</tr>';
            } else {
                // Manejo de errores si no hay filas o hay un problema
                $error = oci_error($stid);
                echo 'Error: ' . $error['message'];
            }
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
                    mensaje.style.display = 'transparent';
                }, 2000);
            }
        });
    </script>
</body>
</section>
</html>
