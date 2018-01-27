<?php
namespace UserEx\Todo\Core\TwigExtensions;

use Pimple\Container;

class RouterTwigExtension extends \Twig_Extension
{
    protected $container = null;
    
    public function __construct(Container $container)
    {
        $this->container = $container;
    }
    
    public function getFunctions()
    {
        return array(
            new \Twig_Function('path', array($this->container['router'], 'getUrl')),
        );
    }
    
}