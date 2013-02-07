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

    /**
     * @var array $status
     *
     * @ORM\Column(name="status", type="string", length=255, columnDefinition="ENUM('1', '0')")
     */
    private $status;
    
    /**
     * @ORM\Column(name="usertype", type="string", length=255, columnDefinition="ENUM('1', '0')")
     */
    private $usertype;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     */
    private $first_name;
    
    /**
     * @ORM\Column(type="integer", length=11)
     * 
     */
    private $timezone;        
    
     /**
     * @ORM\Column(type="string", length=255)
     * 
     */
    private $pbw_daily;   
 
    /**
     * @ORM\Column(type="string", length=255)
     * 
     */
    private $pbw_rate; 
    
    /**
     * @ORM\Column(name="pbw_check", type="string", length=255, columnDefinition="ENUM('1', '0')")
     * 
     */
    private $pbw_check;
    
     /**
     * @ORM\Column(type="string", length=255)
     * 
     */
    private $pbh_daily;   
 
    /**
     * @ORM\Column(type="string", length=255)
     * 
     */
    private $pbh_rate; 
    
    /**
     * @ORM\Column(name="pbh_check", type="string", length=255, columnDefinition="ENUM('1', '0')")
     * 
     */
    private $pbh_check;           

    /**
     * @ORM\Column(type="string", length=255)
     * 
     */
    private $phone;
    
    /**
     * @ORM\Column(type="string", length=255)
     * 
     */
    private $country; 
    
    /**
     * @ORM\Column(type="text")
     * 
     */
    private $fm_address;            

    /**
     * @ORM\Column(type="text")
     * 
     */
    private $contact_close;
    
    /**
     * @ORM\Column(type="datetime")
     */    
    private $signup_date;
    
    
    public function getId()
    {
        return $this->id;
    }
    public function setContactClose($contact)
    {
        $this->contact_close = $contact;
    } 
    public function setFmAddress($address)
    {
        $this->fm_address = $address;
    }    
    
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }      

    public function setCountry($country)
    {
        $this->country = $country;
    } 
      
    public function setPbwDaily($value)
    {
        $this->pbw_daily = $value;
    }
    
    public function setPbhDaily($value)
    {
        $this->pbh_daily = $value;
    }    
    
    public function setPbwRate($value)
    {
        $this->pbw_rate = $value;
    }
    
    public function setPbhRate($value)
    {
        $this->pbh_rate = $value;
    }
    
    public function setPbwCheck($value)
    {
        $this->pbw_check = $value;
    }        

    public function setPbhCheck($value)
    {
        $this->pbh_check = $value;
    }
        
    public function setTimezone($zone)
    {
        $this->timezone = $zone;
    }
        
    public function setFirstname($name)
    {
        $this->first_name = $name;
    }
    
    public function setUsername($name){
        $this->username = $name;
    }
    
    public function setPassword($password){
        $this->password = $password;
    }        

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setUsertype($type)
    {
        $this->usertype = $type;
    }
    
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }    
        
    public function getStatus()
    {
        return $this->status;
    }
    
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
    
    
}