<?php

// Connexion à la base de données hébergée sur InfinityFree.
$host = 'sql301.infinityfree.com';
$database = 'if0_43462867_Events_Site1';
$username = 'if0_43462867';
$password = 'R61uFycKJlUvYw';

try {
    $db = new PDO(
        "mysql:host={$host};dbname={$database};charset=utf8mb4",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    http_response_code(500);
    exit('La connexion à la base de données est momentanément indisponible.');
}
