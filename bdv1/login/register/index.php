<?php
// Añade estas líneas al principio del archivo para ver errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluye registrar.php
require_once __DIR__ . '/registrar.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $resultado = registrar_usuario($nombre, $email, $password);

    if ($resultado === true) {
        // Registro exitoso
        header("Location: ../login.php?registro=exitoso");
        exit();
    } else {
        // Si hay un error, guardarlo para mostrarlo en el formulario
        $error = $resultado;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <form method="post">
        <h2>ReedRealms</h2>
        <p>Inicia tu registro</p>
        
        <?php
        if (isset($error)) {
            echo '<div class="error">' . $error . '</div>';
        }
        ?>

        <div class="input-wrapper">
            <input type="text" name="nombre" placeholder="Nombre" required>
        </div>

        <div class="input-wrapper">
            <input type="email" name="email" placeholder="Correo" required>
        </div>

        <div class="input-wrapper">
            <input type="password" name="password" placeholder="Contraseña" required>
        </div>

        <input class="btn" type="submit" name="register" value="Registrar">
    </form>
</body>
</html>