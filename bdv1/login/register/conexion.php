<?php
$server = "localhost";
$user = "root";
$pass = "";
$db = "reedrealmstore";

// Conexión a la base de datos
$conexion = new mysqli($server, $user, $pass, $db);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
} 

?>