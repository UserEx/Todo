<?php
namespace UserEx\Todo\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 * 
 * @ORM\Entity()
 * @ORM\Table(name="user")
 */
class User 
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
     * @ORM\Column(name="password_hash", type="string", length=60)
     */
    private $pwdhash = null;
    
    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=22, nullable=true)
     */
    private $salt;
    
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=25, unique=true)
     */
    private $name;
    
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
     * Set pwdhash
     *
     * @param string $pwdhash
     *
     * @return User
     */
    public function setPwdhash($pwdhash)
    {
        $this->pwdhash = $pwdhash;
        
        return $this;
    }
    
    /**
     * Get pwdhash
     *
     * @return string
     */
    public function getPwdhash()
    {
        return $this->pwdhash;
    }
    
    /**
     * Set name
     *
     * @param string $name
     *
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;
        
        return $this;
    }
    
    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
        
    
    /**
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }
    
    /**
     * @param string $salt
     *
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function generateSalt() 
    {
        $salt = '';
        $length = rand(5,10);
        for($i=0; $i<$length; $i++) {
            $salt .= chr(rand(33,126));
        }
        
        return $salt;
    }
    
    /**
     * @param string $password
     * 
     * @return User
     */
    public function setPassword($password) 
    {
        $this->setSalt($this->generateSalt());
        $this->pwdhash = sha1(sha1($password) . $this->getSalt());
        
        return $this;
    }
    
    /**
     * @param string $password
     * 
     * @return boolean
     */
    public function checkPassword($password)
    {
        return $this->pwdhash === sha1(sha1($password) . $this->getSalt());
    }
    
}
