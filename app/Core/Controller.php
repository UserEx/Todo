<?php
namespace UserEx\Todo\Core;

use Pimple\Container;

class Controller
{
    protected $container = null;
    
    public function __construct(Container $container) {
        $this->container = $container;
    }
    
    protected function view(string $temlate, array $params) 
    {        
        return $this->container['twig']->render($temlate, $params); 
    }   
}