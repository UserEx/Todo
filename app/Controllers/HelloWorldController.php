<?php
namespace UserEx\Todo\Controllers;

use UserEx\Todo\Core\Controller;
use Nette\Http\Request;
use UserEx\Todo\Core\Response;
use UserEx\Todo\Core\RedirectResponse;

class HelloWorldController extends Controller
{
    /**
     * @param Request $request
     * 
     * @return \UserEx\Todo\Core\RedirectResponse|\UserEx\Todo\Core\Response
     */
    public function indexAction (Request $request)
    {   
        $router = $this->container['router'];
        
        if (!$this->container['user']) {
            return new RedirectResponse($router->getUrl('login'));
        }
        
        return new Response($this->view('hello_world.html.twig', array('msg' => 'Hello, world!!!')));
    }
}