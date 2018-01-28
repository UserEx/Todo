<?php
namespace UserEx\Todo\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Entity(repositoryClass="UserEx\Todo\Repositories\TodoRepository")
 * @ORM\Table(name="todo")
 */
class Todo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=512)
     */
    private $title = null;
    
    /**
     * User
     *
     * @ORM\ManyToOne(targetEntity="UserEx\Todo\Entities\User", fetch="EAGER")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="complited", type="boolean")
     */
    private $completed = false;
    
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * 
     * @return Todo
     */
    public function setTitle($title)
    {
        $this->title = $title;
        
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * 
     * @return Todo
     */
    public function setUser($user)
    {
        $this->user = $user;
        
        return $this;
    }

    /**
     * @return boolean
     */
    public function isCompleted()
    {
        return $this->completed;
    }

    /**
     * @param boolean $completed
     * 
     * @return Todo
     */
    public function setCompleted($completed)
    {
        $this->completed = $completed;
        
        return $this;
    }
    
    
    public function toArray()
    {
        return array(
            'id' => $this->getId(), 
            'title' => $this->getTitle(), 
            'completed' => $this->isCompleted()
        );
    }
}
