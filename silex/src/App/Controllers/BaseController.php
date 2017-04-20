<?php
namespace App\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Repositories\FilesRepository;
use Silex\Application;

class BaseController
{
    protected $container;
    
    public function __construct(Application $app)
    {
        $this->container = $app;
    }
    
    /**
     * @return FileRepository
     */
    protected function getFilesRepository()
    {
        return $this->container['repository.file'];
    }
    
    /**
     * @param  string $message Error message
     */
    public function throw404($message = 'Page not found')
    {
        throw new NotFoundHttpException($message);
    }
    
    /**
     * @param  string $routeName  The name of the route
     * @param  array  $parameters Route variables
     * @param  bool   $absolute
     * @return string A URL
     */
    public function generateUrl($routeName, array $parameters = array(), $absolute = false)
    {
        return $this->container['url_generator']->generate($routeName, $parameters, $absolute);
    }
}