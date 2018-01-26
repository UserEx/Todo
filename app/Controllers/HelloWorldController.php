<?php
namespace UserEx\Todo\Controllers;

use UserEx\Todo\Core\Controller;
use Nette\Http\Request;
use UserEx\Todo\Core\Response;

class HelloWorldController extends Controller
{
    public function indexAction (Request $request)
    {   
        return new Response('Hello, world!');
    }
}