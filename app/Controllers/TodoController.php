<?php
namespace UserEx\Todo\Controllers;

use UserEx\Todo\Core\Controller;
use Nette\Http\Request;
use UserEx\Todo\Core\Response;
use UserEx\Todo\Core\RedirectResponse;
use UserEx\Todo\Core\JsonResponse;
use UserEx\Todo\Entities\Todo;

class TodoController extends Controller
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
        
        return new Response($this->view('todo.html.twig', array()));
    }
    
    /**
     * @param Request $request
     * 
     * @return \UserEx\Todo\Core\JsonResponse
     */
    public function addAction(Request $request)
    {        
        if (!$user = $this->container['user']) {
            return new JsonResponse(array('msg' => 'Unauthorized'), Response::S401_UNAUTHORIZED);
        }
        
        $todoTitle = trim($request->getPost('todo', ''));
        
        if (!$todoTitle) {
            return new JsonResponse(array('msg' => 'require todo'), Response::S400_BAD_REQUEST);
        }
        
        $todo = new Todo();
        $todo->setTitle($todoTitle);
        $todo->setUser($user);
        
        $this->container['em']
            ->getRepository('UserEx\Todo\Entities\Todo')
            ->add($todo);
        
        return new JsonResponse(array(
            'todo' => $todo->toArray(),
            'status' => 'OK',
            'code' => 200
        ));
    }
    
    /**
     * @param Request $request
     *
     * @return \UserEx\Todo\Core\JsonResponse
     */
    public function deleteAction(Request $request)
    {
        if (!$user = $this->container['user']) {
            return new JsonResponse(array('msg' => 'Unauthorized'), Response::S401_UNAUTHORIZED);
        }
        
        $todoId = $request->getPost('todo_id');
        
        if (!$todoId) {
            return new JsonResponse(array('msg' => 'require todo_id'), Response::S400_BAD_REQUEST);
        }
        
        $this->container['em']
            ->getRepository('UserEx\Todo\Entities\Todo')
            ->delete($todoId);
        
        return new JsonResponse(array(
            'status' => 'OK',
            'code' => 200
        ));
    }
    
    public function getListAction(Request $request) {
        if (!$user = $this->container['user']) {
            return new JsonResponse(array('msg' => 'Unauthorized'), Response::S401_UNAUTHORIZED);
        }
        
        $todos = $this->container['em']
            ->getRepository('UserEx\Todo\Entities\Todo')
            ->getTodoList($user);
        
        $todoList = array();
        
        foreach ($todos as $todo) {
            $todoList[] = $todo->toArray();
        }
           
        return new JsonResponse(array(
            'todo_list' => $todoList,
            'status' => 'OK',
            'code' => 200
        ));
    }
    
    public function setComplited(Request $request)
    {
        if (!$user = $this->container['user']) {
            return new JsonResponse(array('msg' => 'Unauthorized'), Response::S401_UNAUTHORIZED);
        }
        
        
    }
}