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

$app->post('/update-persona/:id/:brand', function ($id, $brand) use ($app, $response) {

    try {

        if (empty($id) || empty($brand)) {
            // Mising Parameters
            return $response->withJson([
                'estado' => 0,
                'mensaje' => "Ha ocurrido un error al procesar la solicitud"
            ], 400);
        }

        $credenciales = Credenciales::where('brand', $brand)->first();

        if (empty($credenciales)) {
            return $response->withJson([
                'estado' => 0,
                'mensaje' => "Ha ocurrido un error al procesar la solicitud"
            ], 400);
        }

        // Create signed Json Web Token
        $jwt = JWT::encode([
            'client_id' => $credenciales->client_id,
            'secret_id' => $credenciales->secret_id,
        ], $_ENV['JWT_KEY']);

        // Make a request to the webservice using the JWT as part of the authorization
        $httpClient = $this->get('HttpClient');
        $responseFromWebService = $httpClient->request('GET', 'https://example.com/webservice', [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwt,
                'Accept' => 'application/json',
            ]
        ]);

        // Retrieve the response and decode it
        $data = json_decode($response->getBody(), true);

        // Update Persona in the DB
        $persona = Persona::find($id);
        $persona->nombre = $data['nombre'];
        $persona->apellido = $data['apellido'];
        $persona->edad = $data['edad'];
        $persona->telefono = $data['telefono'];


        if ($persona->save()) {
            return $response->withJson([
                'estado' => 1,
                'mensaje' => "Datos actualizados correctamente"
            ]);
        } else {
            return $response->withJson([
                'estado' => 0,
                'mensaje' => "Ha ocurrido un error al procesar la solicitud"
            ]);
        }
    } catch (\Exception $e) {
        return $response->withJson([
            'estado' => 0,
            'mensaje' => "Ha ocurrido un error al procesar la solicitud"
        ], $e->getCode());
    }
});

// Run app
$app->run();
