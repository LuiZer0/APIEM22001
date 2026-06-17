<?php

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/conexion.php';

$app = AppFactory::create();

$app->addBodyParsingMiddleware();

/* Ruta principal */
$app->get('/', function (Request $request, Response $response) {
    $mensaje = [
        "mensaje" => "API Casa Top funcionando correctamente"
    ];

    $response->getBody()->write(json_encode($mensaje));
    return $response->withHeader('Content-Type', 'application/json');
});

/* GET: listar todos los vehículos */
$app->get('/vehiculos', function (Request $request, Response $response) {
    try {
        $sql = "SELECT 
                    v.Placa,
                    v.ModeloVehiculo,
                    v.Color,
                    v.AnioFabricacion,
                    v.Kilometraje,
                    v.PrecioOriginal,
                    v.IdMarca,
                    m.DescripMarca,
                    m.PaisMarca,
                    m.SitioWebOficial
                FROM Vehiculos v
                INNER JOIN MarcasVehiculos m
                ON v.IdMarca = m.IdMarca";

        $db = getConnection();
        $stmt = $db->query($sql);
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $response->getBody()->write(json_encode($datos));
        return $response->withHeader('Content-Type', 'application/json');

    } catch (Exception $e) {
        $response->getBody()->write(json_encode([
            "error" => "Error al listar vehículos",
            "detalle" => $e->getMessage()
        ]));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(500);
    }
});

/* POST: agregar vehículo */
$app->post('/vehiculos', function (Request $request, Response $response) {
    try {
        $datos = $request->getParsedBody();

        $sql = "INSERT INTO Vehiculos 
                (Placa, ModeloVehiculo, Color, AnioFabricacion, Kilometraje, PrecioOriginal, IdMarca)
                VALUES 
                (:Placa, :ModeloVehiculo, :Color, :AnioFabricacion, :Kilometraje, :PrecioOriginal, :IdMarca)";

        $db = getConnection();
        $stmt = $db->prepare($sql);

        $stmt->execute([
            ':Placa' => $datos['Placa'],
            ':ModeloVehiculo' => $datos['ModeloVehiculo'],
            ':Color' => $datos['Color'],
            ':AnioFabricacion' => $datos['AnioFabricacion'],
            ':Kilometraje' => $datos['Kilometraje'],
            ':PrecioOriginal' => $datos['PrecioOriginal'],
            ':IdMarca' => $datos['IdMarca']
        ]);

        $respuesta = [
            "mensaje" => "Vehículo agregado correctamente"
        ];

        $response->getBody()->write(json_encode($respuesta));
        return $response->withHeader('Content-Type', 'application/json');

    } catch (Exception $e) {
        $response->getBody()->write(json_encode([
            "error" => "Error al agregar vehículo",
            "detalle" => $e->getMessage()
        ]));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(500);
    }
});

/* POST: agregar marca */
$app->post('/marcas', function (Request $request, Response $response) {
    try {
        $datos = $request->getParsedBody();

        $sql = "INSERT INTO MarcasVehiculos 
                (IdMarca, DescripMarca, PaisMarca, SitioWebOficial)
                VALUES 
                (:IdMarca, :DescripMarca, :PaisMarca, :SitioWebOficial)";

        $db = getConnection();
        $stmt = $db->prepare($sql);

        $stmt->execute([
            ':IdMarca' => $datos['IdMarca'],
            ':DescripMarca' => $datos['DescripMarca'],
            ':PaisMarca' => $datos['PaisMarca'],
            ':SitioWebOficial' => $datos['SitioWebOficial']
        ]);

        $respuesta = [
            "mensaje" => "Marca agregada correctamente"
        ];

        $response->getBody()->write(json_encode($respuesta));
        return $response->withHeader('Content-Type', 'application/json');

    } catch (Exception $e) {
        $response->getBody()->write(json_encode([
            "error" => "Error al agregar marca",
            "detalle" => $e->getMessage()
        ]));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(500);
    }
});

/* GET: buscar marca específica */
$app->get('/marcas/{id}', function (Request $request, Response $response, array $args) {
    try {
        $id = $args['id'];

        $sql = "SELECT * FROM MarcasVehiculos WHERE IdMarca = :IdMarca";

        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':IdMarca' => $id
        ]);

        $marca = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($marca) {
            $response->getBody()->write(json_encode($marca));
        } else {
            $response->getBody()->write(json_encode([
                "mensaje" => "Marca no encontrada"
            ]));
        }

        return $response->withHeader('Content-Type', 'application/json');

    } catch (Exception $e) {
        $response->getBody()->write(json_encode([
            "error" => "Error al buscar marca",
            "detalle" => $e->getMessage()
        ]));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(500);
    }
});

$app->run();
