<?php
require '../vendor/autoload.php';

use App\Src\Models\Credenciales;
use App\Src\Models\Persona;

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

$app->post('/update-persona/{id}/{brand}', function ($request, $response, $args) {

    $id = $args['id'];
    $brand = $args['brand'];

    $credenciales = Credenciales::where('brand', $brand)->first();

    if (empty($credenciales)) {
        return $response->withJson([
            'estado' => 0,
            'mensaje' => 'No se encontraron las credenciales para esta brand'
        ], 404);
    }
});

// Run app
$app->run();
