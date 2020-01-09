<?php

header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Headers:X-Request-With');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

require 'vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use DI\ContainerBuilder;
use Selective\Config\Configuration;
use Cake\Database\Connection;
use Psr\Container\ContainerInterface;
use Slim\Exception\HttpUnauthorizedException;

include_once './src/entities/User.php';
include_once './src/entities/Student.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$containerBuilder = new ContainerBuilder();

$containerBuilder->addDefinitions([
    Configuration::class => function () {
        return new Configuration(require __DIR__ . '/src/settings.php');
    },
    Connection::class => function (ContainerInterface $container) {

        return new Connection($container->get(Configuration::class)->getArray('db'));
    },
    PDO::class => function (ContainerInterface $container) {
        $db = $container->get(Connection::class);
        $driver = $db->getDriver();
        $driver->connect();
        return $driver->getConnection();
    }
]);

$container = $containerBuilder->build();

AppFactory::setContainer($container);

$app = AppFactory::create();

$app->addErrorMiddleware(true, true, true);

$app->add(new Tuupola\Middleware\JwtAuthentication([
    "path" => "/api",
    "attribute" => "decoded_token_data",
    "secret" => $_ENV["JWT_SECRET"],
    "algorithm" => ["HS256"],
    "error" => function ($response, $arguments) {
        $data["status"] = "error";
        $data["message"] = $arguments["message"];

        $newResponse = $response->withHeader('Content-type', 'application/json');
        $newResponse->getBody()->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        return $newResponse;
    },
    "before" => function ($request, $params) use ($container) {
        $user = new User($container->get(PDO::class));
        $user->handleAuthToken($params['decoded']['username'], $params['decoded']['password'], false, false);
        if ($params['token'] !== $user->token) {
            throw new HttpUnauthorizedException($request);
        }
    },
]));

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->post('/auth', function (Request $request, Response $response) {
    $input = $request->getParsedBody();

    $user = new User($this->get(PDO::class));
    $user->handleAuthToken($input['username'], $input['password']);

    if (!$user->token) {
        $response->getBody()->write(json_encode(['error' => true, 'message' => 'These credentials do not match our records.']));
        return $response;
    }

    $response->getBody()->write(json_encode(['token' => $user->token]));
    return $response;
});

$app->delete("/auth", function (Request $request, Response $response) {
    $input = $request->getParsedBody();

    $user = new User($this->get(PDO::class));
    $user->fetchUserByToken($input["token"]);
    $user->clearToken();

    $response->getBody()->write(json_encode(['token' => ""]));
    return $response;
});

$app->get('/api/users', function (Request $request, Response $response) {
    $input = $request->getQueryParams();

    $page = (isset($input['page']) && $input['page'] > 0) ? $input['page'] : 1;
    $limit = isset($input['limit']) ? $input['limit'] : 5;

    $student = new Student($this->get(PDO::class));
    $students = $student->getStudents($page, $limit);

    $response->getBody()->write(json_encode($students));
    return $response;
});

$app->run();
