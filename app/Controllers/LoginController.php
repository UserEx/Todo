<?php
namespace UserEx\Todo\Controllers;

use UserEx\Todo\Core\Controller;
use Nette\Http\Request;
use UserEx\Todo\Core\Response;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use UserEx\Todo\Core\RedirectResponse;

class LoginController extends Controller
{
    /**
     * @param Request $request
     * 
     * @return \UserEx\Todo\Core\Response
     */
    public function indexAction (Request $request)
    {   
        $router = $this->container['router'];
        
        if ($this->container['user']) {
            return new RedirectResponse($router->getUrl('hello_wold'));
        }
        
        return new Response($this->view('login.html.twig', array()));
    }
    
    /**
     * @param Request $request
     * 
     * @return \UserEx\Todo\Core\Response
     */
    public function signInAction(Request $request)
    {
        $router = $this->container['router'];
        
        if ($this->container['user']) {
            return new RedirectResponse($router->getUrl('hello_wold'));
        }
        
        $username = $request->getPost('username', null);
        $password = $request->getPost('password', null);
        
        $username = trim($username);
        
        $validMsg = array();
        if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
            $validMsg[] = 'Недопустимые символы в имени пользователя(допустимы латинские буквы и цифры)';
        }
        
        if (!strlen($password)) {
            $validMsg[] = 'Пароль не может быть пустым';
        }
                
        $response = null;
        
        if (!$validMsg) {            
            /** @var $auth \UserEx\Todo\Core\AuthentificationService */
            $auth = $this->container['authservice'];
            
            if (!$auth->login($username, $password)) {
                $validMsg[] = 'Неверное имя или пароль';
            } else {                
                $response = new RedirectResponse($router->getUrl('hello_wold'));
            }
        }
        
        if (!$response) {
            $response = new Response($this->view('login.html.twig', array('messages' => $validMsg)));
        }
        
        return $response;
    }
    
    public function logoutAction(Request $request) {
        /** @var $auth \UserEx\Todo\Core\AuthentificationService */
        $auth = $this->container['authservice'];
        
        $auth->logout($request);
        
        return new RedirectResponse($this->container['router']->getUrl('login'));
    }
}