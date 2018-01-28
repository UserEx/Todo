<?php
namespace UserEx\Todo\Repositories;

use Doctrine\ORM\EntityRepository;
use UserEx\Todo\Entities\User;
use UserEx\Todo\Entities\Todo;

/**
 * @author ildar
 */
class TodoRepository extends EntityRepository
{
    /**
     * @param Todo $todo
     */
    public function add(Todo $todo) 
    {
        $this->getEntityManager()->persist($todo);
        $this->getEntityManager()->flush();
    }
    
    /**
     * @param User $user
     * 
     * @return array
     */
    public function getTodoList(User $user) 
    {
        return $this->findBy(array('user' => $user));
    }
    
    /**
     * @param User $user
     * @param bool $completed
     */
    public function toggleAllTodo(User $user, $completed)
    {
        $todoList = $this->getTodoList($user);
        
        foreach ($todoList as $todo) {
            $todo->setCompleted($completed);
        }
        $this->getEntityManager()->flush();
    }
    
    /**
     * @param User $user
     */
    public function deleteCompleted(User $user)
    {
        $todos = $this->findBy(array('user' => $user, 'completed' => true));
        
        $em = $this->getEntityManager();
        
        foreach ($todos as $todo) {
            $em->remove($todo);
        }
        
        $em->flush();
    }
}