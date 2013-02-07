<?php
namespace Start\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Start\StoreBundle\Repository\DocRepository")
 * @ORM\Table(name="documentation")
 */
class Documentation
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
    protected $doc_title;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $doc_body; 
    
    public function id(){
        return $this->id;
    }
    
    public function doc_title(){
        return $this->doc_title;
    }
    
    public function doc_body(){
        return $this->doc_body;
    }
    
    public function setTitle($title)
    {
        $this->doc_title = $title;
    }
    
    public function setBody($body)
    {
        $this->doc_body = $body;
    }    
}