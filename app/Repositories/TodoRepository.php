<?php
namespace UserEx\Todo\Repositories;

use Doctrine\ORM\EntityRepository;
use UserEx\Todo\Entities\User;
use UserEx\Todo\Entities\Todo;


class TodoRepository extends EntityRepository
{
    public function add(Todo $todo) 
    {
        $this->getEntityManager()->persist($todo);
        $this->getEntityManager()->flush();
    }
    
    public function delete($id)
    {
        $todo = $this->find($id);
        if ($todo) {
            $this->getEntityManager()->remove($todo);
            $this->getEntityManager()->flush();
        }
    }
    
    public function getTodoList(User $user) 
    {
        return $this->findBy(array('user' => $user));
    }
}