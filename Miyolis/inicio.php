<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
         body {
            background-color: #f08080; /* Salmon */
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 400px;
            margin: auto;
            margin-top: 100px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4caf50; /* Green */
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .error-message {
            background-color: #ff6666;
            color: #ffffff;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Iniciar Sesión</h2>
        <form action="login.php" method="post">
            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" required>

            <label for="clave">Contraseña:</label>
            <input type="password" id="clave" name="clave" required>

            <button type="submit">Iniciar Sesión</button>
        </form>

        <div id="mensaje-error" style="display: <?php echo $_SESSION['error_display']= "block"; ?>;">
            <?php echo  $_SESSION["error_message"] = "Correo o contraseña incorrectos"; ?>
        </div>

        <p>¿No tienes una cuenta? <a href="registro.php">Regístrate aquí</a></p>
        <p><a href="recuperar.php">¿Olvidaste tu contraseña?</a></p>
    </div>

    <script>
        // Mostrar el mensaje de error por 2 segundos y luego ocultarlo
        document.addEventListener('DOMContentLoaded', function () {
            var mensaje = document.getElementById('mensaje-error');
            if (mensaje.innerHTML !== '') {
                mensaje.style.display = 'block';
                setTimeout(function () {
                    mensaje.style.display = 'none';
                }, 4000);
            }
        });
    </script>
</body>
</html>
