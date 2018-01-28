<?php
namespace UserEx\Todo\Core;

use Pimple\Container;

/**
 * @author ildar
 */
class Controller
{
    /**
     * @var Container
     */
    protected $container = null;
    
    /**
     * @param Container $container
     */
    public function __construct(Container $container) {
        $this->container = $container;
    }
    
    /**
     * @param string $temlate
     * @param array $params
     * 
     * @return string
     */
    protected function view(string $temlate, array $params) 
    {        
        return $this->container['twig']->render($temlate, $params); 
    }
}