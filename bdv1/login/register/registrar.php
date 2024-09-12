<?php

// Usa una ruta absoluta para incluir conexion.php
require_once __DIR__ . '/conexion.php';

function registrar_usuario($nombre, $email, $password) {
    global $conexion; // Asegúrate de que $conexion esté disponible dentro de la función

    // Hashear la contraseña
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Preparar la consulta SQL
    $consulta = "INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)";
    
    if ($stmt = $conexion->prepare($consulta)) {
        $stmt->bind_param("sss", $nombre, $email, $hashed_password);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            $error = "Error al registrar: " . $stmt->error;
            $stmt->close();
            return $error;
        }
    } else {
        return "Error en la preparación de la consulta: " . $conexion->error;
    }
}

?>