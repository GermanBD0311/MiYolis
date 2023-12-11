<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="tablas.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CLIENTES</title>
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
    <h1>CLIENTES</h1>
    <div class="botones">

    <button type="submit" name="principal" id="principal"><a href="Clientes.html">Volver </a></button>
    </div>
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
        $idCliente = isset($_POST['ID_CLIENTE']) ? $_POST['ID_CLIENTE'] : null;

        if (isset($_POST['insertar'])) {
            // Realizar la inserción
            $nombre = $_POST['NOMBRE']; 
            $apellidoP = $_POST['APELLIDO_P'];
            $apellidoM = $_POST['APELLIDO_M'];
            $telefono = $_POST['TELEFONO'];

            $insertar = "INSERT INTO clientes (ID_CLIENTE, NOMBRE, APELLIDO_P, APELLIDO_M, TELEFONO)
                         VALUES ('$idCliente', '$nombre', '$apellidoP', '$apellidoM', '$telefono')";
            $stid = oci_parse($conexion, $insertar);
            oci_execute($stid);
            $mensaje = 'Inserción realizada con éxito.';

            // Consultar y mostrar los resultados actualizados
            $consulta = 'SELECT ID_CLIENTE, NOMBRE, APELLIDO_P, APELLIDO_M, TELEFONO FROM CLIENTES ORDER BY ID_CLIENTE';
            $stid = oci_parse($conexion, $consulta);
            oci_execute($stid);
        } elseif (isset($_POST['eliminar'])) {
            // Realizar la eliminación
            $eliminar = "DELETE FROM clientes WHERE ID_CLIENTE = '$idCliente'";
            $stid = oci_parse($conexion, $eliminar);
            oci_execute($stid);
            $mensaje = 'Eliminación realizada con éxito.';

            // Consultar todos los clientes después de la eliminación
            $consulta = 'SELECT ID_CLIENTE, NOMBRE, APELLIDO_P, APELLIDO_M, TELEFONO FROM CLIENTES ORDER BY ID_CLIENTE';
            $stid = oci_parse($conexion, $consulta);
            oci_execute($stid);
        } elseif (isset($_POST['actualizar'])) {
            // Realizar la actualización
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

            // Consultar y mostrar los resultados actualizados
            $consulta = "SELECT ID_CLIENTE, NOMBRE, APELLIDO_P, APELLIDO_M, TELEFONO FROM CLIENTES WHERE ID_CLIENTE = '$idCliente'";
            $stid = oci_parse($conexion, $consulta);
            oci_execute($stid);
        } elseif (isset($_POST['buscar'])) {
            // Si es una búsqueda, consulta todos los clientes o uno específico
            if ($idCliente !== null) {
                $consulta = "SELECT ID_CLIENTE, NOMBRE, APELLIDO_P, APELLIDO_M, TELEFONO FROM CLIENTES WHERE ID_CLIENTE = '$idCliente'";
            } else {
                $consulta = 'SELECT ID_CLIENTE, NOMBRE, APELLIDO_P, APELLIDO_M, TELEFONO FROM CLIENTES ORDER BY ID_CLIENTE';
            }

            $stid = oci_parse($conexion, $consulta);
            oci_execute($stid);
        
        }elseif (isset ($_POST ['consultar'])){

            $busqueda = "SELECT ID_CLIENTE, NOMBRE, APELLIDO_P, APELLIDO_M, TELEFONO FROM CLIENTES ORDER BY ID_CLIENTE ";
            
            $stid = oci_parse($conexion, $busqueda);
            oci_execute($stid);
        
        }
    } else {
        // Consultar todos los clientes por defecto
        $consulta = 'SELECT ID_CLIENTE, NOMBRE, APELLIDO_P, APELLIDO_M, TELEFONO FROM CLIENTES ORDER BY ID_CLIENTE';
        $stid = oci_parse($conexion, $consulta);
        oci_execute($stid);
    }
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
        // Cambio en la verificación de filas
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
</
