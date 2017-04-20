<?php

namespace App\Loaders;

use App\Controllers\FilesController;
use Silex\Application;

class RoutesLoader
{
    private $app;
    
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->instantiateControllers();
        
    }
    
    private function instantiateControllers()
    {
        $this->app['files.controller'] = function()
        {
            return new FilesController($this->app);
        };
    }
    
    public function bindRoutesToControllers()
    {
        $api = $this->app["controllers_factory"];
        $app = $this->app;
        $api->get('/files', "files.controller:getAll");
        $api->get('/files/{id}', "files.controller:getOne")->bind('files_show');
        $api->get('/files/{id}/metadata', "files.controller:getMetadata");
        $api->post('/files', "files.controller:insert");
        $api->match('/files/{id}', "files.controller:update")->method('PATCH');
        
        $this->app->mount($this->app["api.endpoint"] . '/' . $this->app["api.version"], $api);
    }
}