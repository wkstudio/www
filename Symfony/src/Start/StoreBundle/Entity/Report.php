<?php
namespace Start\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Start\StoreBundle\Repository\ReportRepository")
 * @ORM\Table(name="report")
 */
class Report
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $postdata;
    
    /**
     * @ORM\Column(type="integer", length=11)
     */
    private $minutes; 

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private $words;     
 
     /**
     * @ORM\Column(type="text")
     */
    private $content;
   
    /**
     * @ORM\Column(type="integer", length=11)
     */
    private $uid;     
    
    public function getId()
    {
        return $this->id;
    }
    public function setUid($uid)
    {
        $this->uid = $uid;
    }
    
    public function setPostData($date)
    {
        $this->postdata = $date;
    }
    
    public function getPostData()
    {
        return $this->postdata;
    }   
    
    public function setMinutes($minutes)
    {
        $this->minutes = $minutes;
    }

    public function getMinutes()
    {
        return $this->minutes;
    }
    
    public function setWords($words)
    {
        $this->words = $words;
    }
    
    public function getWords()
    {
        return $this->words;
    }    
    
    public function setContent($content)
    {
        $this->content = $content;
    }
    
    public function getCutContent($len = 10)
    {
        return substr($this->content, 0, $len);
    }
}