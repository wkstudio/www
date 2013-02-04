<?php
namespace Start\StoreBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Start\StoreBundle\Repository\UserRepository")
 * @ORM\Table(name="user")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $username;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $password; 
    
    /**
     * @ORM\Column(type="string", length=255)
     * 
     */
    protected $roles;       

    public function getRoles(){
        return explode(",", $this->roles); 
    }
    
    public function getPassword(){
        return $this->password;
    }    
    
    public function getSalt(){
        
    }   
    
    public function getUsername(){
        return $this->username;    
    }     
    
    public function eraseCredentials(){
        
    }
    
    public function setUsername($name){
        $this->username = $name;
    }
    
    public function setPassword($password){
        $this->password = $password;
    }    
}