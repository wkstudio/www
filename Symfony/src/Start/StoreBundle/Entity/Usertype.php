<?php
namespace Start\StoreBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Start\StoreBundle\Repository\UserRepository")
 * @ORM\Table(name="usertype")
 */
class Usertype
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $usertype;
    
    public function getId()
    {
        return $this->id;
    }   
    
    public function getUserType()
    {
        return $this->usertype;
    }
    
    public function setUserType($type)
    {
        $this->usertype = $type;
    }

}