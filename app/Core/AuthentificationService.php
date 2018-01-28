<?php
namespace UserEx\Todo\Core;

use Pimple\Container;
use UserEx\Todo\Entities\AuthToken;
use Nette\Http\Request;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;

/**
 * @author ildar
 */
class AuthentificationService
{
    /**
     * @var Container
     */
    protected $container = null;
    
    /**
     * @var EntityRepository
     */
    protected $userRepository = null;
    
    /** 
     * @var EntityRepository
     */
    protected $tokenRepository = null;

    /**
     * @var integer
     */
    protected $tokenLifeTime = 90000;
    
    /**
     * @var EntityManager
     */
    protected $em = null;
    
    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        
        $this->em = $container['em'];
        $this->userRepository = $this->em->getRepository('UserEx\Todo\Entities\User');
        $this->tokenRepository = $this->em->getRepository('UserEx\Todo\Entities\AuthToken');
        $this->tokenLifeTime = $container['config']['auth_token_life_time'] ?: $this->tokenLifeTime;
    }
    
    /**
     * @param string $username
     * @param string $password
     * 
     * @return boolean|\UserEx\Todo\Entities\User
     */
    public function authenticate($username, $password)
    {        
        /** @var $user \UserEx\Todo\Entities\User */
        $user = $this->userRepository->findOneBy(array('name' => $username));
        
         
        return ($user && $user->checkPassword($password)) ? $user : false;
    }
    
    /**
     * @param string $username
     * @param string $password
     * 
     * @return NULL|\UserEx\Todo\Entities\AuthToken
     */
    public function login($username, $password) 
    {
        $authToken = null;
        
        if ($user = $this->authenticate($username, $password)) {
            $token = null;
            do {
                $length = 32;
                for($i=0; $i<$length; $i++) {
                    $token .= chr(rand(33,126));
                }
                $token = $token;
            } while($this->tokenRepository->findOneBy(array('token' => $token)));
            
            echo 'token: ' . $token;
            
            $authToken = new AuthToken();
            $authToken->setToken($token);
            $expires = new \DateTime();
            $expires->setTimestamp(time() + $this->tokenLifeTime);
            $authToken->setExpires($expires);
            $authToken->setUser($user);
            
            $this->em->persist($authToken);
            $this->em->flush();
            
            setcookie(
                'auth', 
                $authToken->getToken(), 
                $authToken->getExpires()->getTimestamp(),
                '/',
                $this->container['config']['host']
            );
        }
        
        return $authToken;
    }
    
    /**
     * @param Request $request
     */
    public function logout(Request $request)
    {
        $token = $request->getCookie('auth');
        if ($token) {
            $authToken = $this->tokenRepository->findOneBy(array('token' => $token));        
            
            if ($authToken) {
                $this->em->remove($authToken);
            }
        }
        setcookie('auth', '', time() - 3600, '/', $this->container['config']['host']);
    }
    
    /**
     * @param string $token
     * 
     * @return boolean
     */
    public function checkToken($token)
    {
        return (bool) $this->tokenRepository->findOneBy(array('token' => $token));
    }
    
    /**
     * @param string $token
     * 
     * @return NULL
     */
    public function getUser($token) 
    {
        $authToken = $this->tokenRepository->findOneBy(array('token' => $token));
        
        return $authToken ? $authToken->getUser() : null;
    }
}