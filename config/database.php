<?php

$host = 'localhost';
$dbname = 'devclass_inventario';
$username = 'devclass_admin_inventario';
$password = 'alumnousil123';

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