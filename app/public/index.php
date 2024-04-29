<?php
require '../vendor/autoload.php';

use Dotenv\Dotenv;
use Firebase\JWT\JWT;
use Illuminate\Database\Capsule\Manager;

use App\Models\Credenciales;
use App\Models\Persona;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$capsule = new Manager;
$capsule->addConnection(require __DIR__ . '/../config/database.php');
$capsule->setAsGlobal();
$capsule->bootEloquent();

// Prepare app
$app = new \Slim\Slim(array(
    'templates.path' => '../templates',
    'log.level' => \Slim\Log::ERROR,
    'log.enabled' => true,
    'log.writer' => new \Slim\Extras\Log\DateTimeFileWriter(array(
        'path' => '../logs',
        'name_format' => 'y-m-d'
    ))
));

// Prepare view
// $app->view(new \Slim\Views\Twig());
// $app->view->parserOptions = array(
//     'charset' => 'utf-8',
//     'cache' => realpath('../templates/cache'),
//     'auto_reload' => true,
//     'strict_variables' => false,
//     'autoescape' => true
// );
// $app->view->parserExtensions = array(new \Slim\Views\TwigExtension());

// Define routes
$app->get('/', function () use ($app) {
    $app->render('index.html');
});

/**
 * Test route
 * This method list the "personas"
 */
$app->get('/list-persona', function () use ($app) {
    $response = $app->response();
    $response->headers->set('Content-Type', 'application/json');

    try {
        $personas = Persona::all();

        $response->status(200);
        $response->body($personas->toJson());
    } catch (\Exception $e) {
        $response->status($e->getCode());
        $response->body(json_encode([
            'mensaje' => "Oops! " . PHP_EOL . $e->getMessage()
        ]));
    }

    return $response;
});

/**
 * Test route
 * This method list the "credenciales"
 */
$app->get('/list-credenciales', function () use ($app) {
    $response = $app->response();
    $response->headers->set('Content-Type', 'application/json');

    try {
        $credenciales = Credenciales::all();

        $response->status(200);
        $response->body($credenciales->toJson());
    } catch (\Exception $e) {
        $response->status($e->getCode());
        $response->body(json_encode([
            'mensaje' => "Oops! " . PHP_EOL . $e->getMessage()
        ]));
    }

    return $response;
});

/**
 * 
 */
$app->post('/update-persona/:id/:brand', function ($id, $brand) use ($app) {

    $response = $app->response();
    $response->headers->set('Content-Type', 'application/json');

    try {

        if (empty($id) || empty($brand)) {
            $response->status(400);
            $response->body(json_encode([
                'estado' => 0,
                'mensaje' => "Ha ocurrido un error al procesar la solicitud"
            ]));
        }

        $credenciales = Credenciales::where('brand', $brand)->first();

        if (empty($credenciales)) {
            $response->status(400);
            $response->body(json_encode([
                'estado' => 0,
                'mensaje' => "Ha ocurrido un error al procesar la solicitud"
            ]));
        }

        // Create signed Json Web Token
        $jwt = JWT::encode([
            'client_id' => $credenciales->client_id,
            'secret_id' => $credenciales->secret_id,
        ], $_ENV['JWT_KEY']);

        // Make a request to the webservice using the JWT as part of the authorization
        $url = 'https://example.com/webservice';

        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => [
                    'Authorization' => 'Bearer ' . $jwt,
                    'Accept' => 'application/json',
                ],
            ],
        ]);

        $responseFromWebService = file_get_contents($url, false, $context);

        // Retrieve the response and decode it
        $data = json_decode($response->getBody(), true);

        // Update Persona in the DB
        $persona = Persona::find($id);
        $persona->nombre = $data['nombre'];
        $persona->apellido = $data['apellido'];
        $persona->edad = $data['edad'];
        $persona->telefono = $data['telefono'];


        if ($persona->save()) {

            $response->status(200);
            $response->body(json_encode([
                'estado' => 1,
                'mensaje' => "Datos actualizados correctamente"
            ]));
        } else {
            $response->status(400);
            $response->body(json_encode([
                'estado' => 0,
                'mensaje' => "Ha ocurrido un error al procesar la solicitud"
            ]));
        }
    } catch (\Exception $e) {

        $response->status($e->getCode());
        $response->body(json_encode([
            'estado' => 0,
            'mensaje' => "Ha ocurrido un error al procesar la solicitud"
        ]));
    }
});

// Run app
$app->run();
