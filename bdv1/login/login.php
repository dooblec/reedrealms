<?php
session_start();
require_once 'config.php';

$error = '';
$debug = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $debug .= "Formulario enviado.\n";
    
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    
    $debug .= "Usuario: $usuario\n";
    
    // Ajustamos la consulta para usar nombres de columnas más comunes
    $sql = "SELECT nombre, password FROM usuarios WHERE nombre = ?";
    
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("s", $usuario);
        
        if ($stmt->execute()) {
            $stmt->store_result();
            
            if ($stmt->num_rows == 1) {
                $stmt->bind_result($nombre_usuario, $hashed_password);
                if ($stmt->fetch()) {
                    $debug .= "Usuario encontrado en la base de datos.\n";
                    $debug .= "Contraseña almacenada: " . $hashed_password . "\n"; // Solo para depuración
                    if ($contrasena === $hashed_password) {
                        $_SESSION['loggedin'] = true;
                        $_SESSION['usuario'] = $nombre_usuario;
                        $debug .= "Autenticación exitosa. Redirigiendo...\n";
                        header("location: pagina/index.html");
                        exit();
                    } else {
                        $error = "La contraseña no es válida.";
                        $debug .= "Contraseña incorrecta. Contraseña ingresada: " . $contrasena . "\n"; // Solo para depuración
                    }
                }
            } else {
                $error = "No se encontró una cuenta con ese nombre de usuario.";
                $debug .= "Usuario no encontrado.\n";
            }
        } else {
            $error = "Oops! Algo salió mal. Por favor, inténtalo de nuevo más tarde.";
            $debug .= "Error en la ejecución de la consulta: " . $stmt->error . "\n";
        }
        
        $stmt->close();
    } else {
        $error = "Error en la preparación de la consulta.";
        $debug .= "Error en la preparación de la consulta: " . $mysqli->error . "\n";
    }
    
    $mysqli->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <h2>ReedRealms</h2>
        <p>Iniciar sesión</p>
        <?php
        if (!empty($error)) {
            echo '<div class="error">' . $error . '</div>';
        }
        ?>
        <div class="input-wrapper">
            <input type="text" name="usuario" placeholder="Usuario" required class="input"> 
        </div>
        <div class="input-wrapper">
            <input type="password" name="contrasena" placeholder="Contraseña" required class="input">
        </div>
        <input class="btn" type="submit" name="login" value="Iniciar Sesión">
        <a href="register/index.php">¿No tienes una cuenta? Regístrate</a>
    </form>
    <?php
    if (!empty($debug)) {
        echo '<pre>' . htmlspecialchars($debug) . '</pre>';
    }
    ?>
</body>
</html>