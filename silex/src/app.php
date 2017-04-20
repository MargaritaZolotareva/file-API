<?php
namespace App;
require_once __DIR__ . '/../vendor/autoload.php';

use Doctrine\DBAL\DriverManager;
use Silex\Provider\HttpCacheServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\RoutingServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Api\ApiProblem;
use App\Api\ApiProblemException;
use App\Loaders\RoutesLoader;
use App\Repositories\FilesRepository;
use Carbon\Carbon;
use \Monolog\Logger;

// configure parameters
$app['root_dir']   = __DIR__ . '/..';
$app['mysql_path'] = $app['root_dir'];

// configure providers
$app->register(new ServiceControllerServiceProvider());
$app->register(new RoutingServiceProvider());
$app->register(new DoctrineServiceProvider(), array(
    'db.options' => array(
        'dbname' => 'silex',
        'user' => 'silex',
        'password' => 'silex',
        'path' => $app['mysql_path']
    )
));

$app->register(new MonologServiceProvider(), array(
    "monolog.logfile" => __DIR__ . "/../storage/logs/" . Carbon::now('Europe/London')->format("Y-m-d") . ".log",
    "monolog.name" => "application"
));

// configure services
$app['repository.file'] = function() use ($app)
{
    return new FilesRepository($app['db']);
};

//mount controllers
$routesLoader = new RoutesLoader($app);
$routesLoader->bindRoutesToControllers();

//configure listeners
$app->error(function(\Exception $e, $statusCode) use ($app)
{
    if (strpos($app['request_stack']->getCurrentRequest()->getPathInfo(), '/api') !== 0) {
        return;
    }
    
    if ($e instanceof ApiProblemException) {
        $apiProblem = $e->getApiProblem();
    } else {
        $statusCode = $e instanceof HttpException ? $e->getStatusCode() : 500;
        if ($app['debug'] && $statusCode == 500) {
            return;
        }
        $apiProblem = new ApiProblem($statusCode);
        if ($e instanceof HttpException) {
            $apiProblem->set('detail', $e->getMessage());
        }
    }
    $response = new JsonResponse($apiProblem->toArray(), $apiProblem->getStatusCode());
    $response->headers->set('Content-Type', 'application/problem+json');
    
    return $response;
});

return $app;
