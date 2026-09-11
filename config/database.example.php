<?php

$host = 'localhost';
$dbname = 'PREFIJO_inventario';
$username = 'PREFIJO_usuario';
$password = 'CAMBIA_ESTE_VALOR';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $error) {
    die('No fue posible conectar con la base de datos.');
}