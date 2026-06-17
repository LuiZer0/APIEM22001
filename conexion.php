<?php

function getConnection() {
    $host = "sql303.infinityfree.com";
    $dbname = "if0_42196501_casatop";
    $user = "if0_42196501";
    $pass = "KzPIwqafckf46kV";

    try {
        $conexion = new PDO(
            "mysql:host=$host;dbname=$dbname;charset=utf8",
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