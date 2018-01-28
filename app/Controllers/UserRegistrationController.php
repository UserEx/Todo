<?php
namespace UserEx\Todo\Controllers;

use UserEx\Todo\Core\Controller;
use Nette\Http\Request;
use UserEx\Todo\Core\Response;
use Doctrine\ORM\EntityManager;
use UserEx\Todo\Entities\User;
use UserEx\Todo\Core\RedirectResponse;

class UserRegistrationController extends Controller
{
    public function indexAction (Request $request)
    {
        $router = $this->container['router'];
        
        if ($this->container['user']) {
            return new RedirectResponse($router->getUrl('todo'));
        }
        
        return new Response($this->view('registration.html.twig', array()));
    }
    
    public function signUpAction (Request $request)
    {
        $router = $this->container['router'];
        
        if ($this->container['user']) {
            return new RedirectResponse($router->getUrl('todo'));
        }
        
        $username = $request->getPost('username', null);
        $password = $request->getPost('password', null);
        $repeat   = $request->getPost('repeat', null);
        
        $username = trim($username);
        
        $validMsg = array();
        if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
            $validMsg[] = 'Недопустимые символы в имени пользователя(допустимы латинские буквы и цифры)';
        }
        
        if (!strlen($password)) {
            $validMsg[] = 'Пароль не может быть пустым';   
        }
        
        if ($password != $repeat) {
            $validMsg[] = 'Введенные пароли не воспадают';
        }
        
        /** @var $em EntityManager */
        $em = $this->container['em'];
        $repository = $em->getRepository('UserEx\Todo\Entities\User');
        
        $response = null;
        
        if (!$validMsg) {
            $users = $repository->findBy(array('name' => $username));
            if ($users) {
                $validMsg[] = 'Пользователь с таким именем уже зарегистрирован';
            } else {
                $user = new User();
                $user->setName($username)
                    ->setPassword($password);
                
                $em->persist($user);
                $em->flush();
                
                /** @var $auth \UserEx\Todo\Core\AuthentificationService */
                $auth = $this->container['authservice'];
                $auth->login($username, $password);
                
                $response = new RedirectResponse($router->getUrl('todo'));
            }
        }        
        
        if (!$response) {
            $response = new Response($this->view('registration.html.twig', array('messages' => $validMsg)));
        }
        
        return $response;
    }
    
    
}