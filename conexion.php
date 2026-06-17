<?php

function getConnection() {
    $host = "gnzxub.h.filess.io";
    $port = "3307";
    $dbname = "casatopem22001_happilydry";
    $user = "casatopem22001_happilydry";
    $pass = "318c8347950579e0fff938ffca3e83214808b2cc4";

    try {
        $conexion = new PDO(
            "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8",
            $user,
            $pass
        );

        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conexion;

    } catch (PDOException $e) {
        echo json_encode([
            "error" => "Error de conexión: " . $e->getMessage()
        ]);
        exit;
    }
}
