<?php
namespace UserEx\Todo\Core;

use Pimple\Container;
use Symfony\Component\Yaml\Yaml;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Nette\Http\IRequest;

class Kernel
{
    protected $container = null;
    
    protected $configFile = __DIR__ . '/../../resources/config/config.yml';
    protected $routeFile =  __DIR__ . '/../../resources/config/routes.yml';
    protected $entitiesPath = __DIR__ . '/../Entities';
    protected $twigTemplatePath = __DIR__ . '/../../resources/views';
    protected $twigCompilationCache = __DIR__ . '/../../cache/twig_compilation_cache';
    
    public function __construct() {
        $this->container = new Container();
        
        $this->loadConfig();
        $this->registryRouter();
        $this->registryORMService();
        $this->registryTemplateEngine();
        
    }
    
    protected function loadConfig()
    {
        $this->container['config'] = Yaml::parseFile($this->configFile);
        $this->container['routes'] = Yaml::parseFile($this->routeFile);
        $this->container['entities_path'] = $this->entitiesPath;
    }
    
    protected function registryORMService()
    {
        $this->container['em'] = function ($c) {
            $config = Setup::createAnnotationMetadataConfiguration($c['entities_path'], false);
            $entityManager = EntityManager::create($c['config']['database'], $config);
            
            return $entityManager;
        };
    }
    
    protected function registryTemplateEngine()
    {
        $this->container['twig'] = function ($c) {
            $loader = new \Twig_Loader_Filesystem($this->twigTemplatePath);
            $twig = new \Twig_Environment($loader, array(
                'cache' => $this->twigCompilationCache,
                'auto_reload' => true,
            ));

            return $twig;
        };
    }
    
    protected function registryRouter()
    {
        $this->container['router'] = function ($c) {
            return new Router($this->container);
        };
    }
    
    protected function registryAuthenticationService()
    {
        
    }
    
    protected function registryAuthorizationService()
    {
        
    }
    
    protected function registry()
    {
        
    }
    
    
    public function handler(IRequest $request)
    {
        /* @var $router Router */
        $router = $this->container['router'];
        list($controller, $action) = $router->getController($request);
        
        $response = $controller->$action($request);
        
        $response->send();
    }
}