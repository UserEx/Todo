<?php
namespace UserEx\Todo\Core;

use Pimple\Container;
use Nette\Http\IRequest;

/**
 * @author ildar
 */
class Router 
{    
    /**
     * @var Container
     */
    protected $container = null;
    
    /**
     * @param Container $container
     */
    public function __construct(Container $container) 
    {
        $this->container = $container;
    }
    
    /**
     * @param IRequest $request
     * 
     * @return string[]|\Pimple\Container[]
     */
    public function getController(IRequest $request)
    {
        $controller = null;
        $action = '';
        
        
        foreach ($this->container['routes'] as $nameRoute => $route) {
            if ($request->getUrl()->getPath() == $route['path'] && 
                $request->getMethod() == $route['method']) {
                
                $controller = new $route['controller']($this->container);
                $action = $route['action'];
            }
        }
        
        return array($controller, $action);
    }
    
    /**
     * @return \Pimple\Container[]
     */
    public function getExposedRoutes()
    {
        $exposedRoutes = array();
        
        foreach ($this->container['routes'] as $routeName => $route)
        {
            if (key_exists('expose', $route) && $route['expose']) {
                $exposedRoutes[$routeName] = $route['path'];
            }
        }
        
        return $exposedRoutes;
    }
    
    /**
     * @param string $routeName
     * @param boolean $absolute
     * 
     * @return string
     */
    public function getUrl($routeName, $absolute = false)
    {
        $url = $this->container['routes'][$routeName]['path'];
        
        return ($absolute ? $this->container['config']['host'] : '') . $url;
    }
}