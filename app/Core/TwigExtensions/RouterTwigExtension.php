<?php
namespace UserEx\Todo\Core\TwigExtensions;

use Pimple\Container;

/**
 * @author ildar
 *
 */
class RouterTwigExtension extends \Twig_Extension
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
     * {@inheritDoc}
     * @see \Twig_Extension::getFunctions()
     */
    public function getFunctions()
    {
        return array(
            new \Twig_Function('path', array($this->container['router'], 'getUrl')),
        );
    }
    
}