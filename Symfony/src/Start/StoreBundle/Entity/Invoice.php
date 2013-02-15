<?php
namespace Start\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Start\StoreBundle\Repository\InvoiceRepository")
 * @ORM\Table(name="invoice")
 */
class Invoice
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
    private $date_from;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $date_to;

    /**
     * @ORM\Column(type="string")
     */
    private $paypal;

    /**
     * @ORM\Column(type="float")
     */
    private $summa;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private $invoice_number;

    /**
     * @ORM\Column(type="string")
     */
    private $pdf;    

    /**
     * @ORM\Column(type="text")
     */
    private $comment;    
    
    /**
     * @ORM\Column(name="paid", type="string", length=255, columnDefinition="ENUM('1', '0')")
     */
    private $paid;    
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $submited_date; 
   
    /**
     * @ORM\Column(type="integer", length=11)
     */
    private $uid;     
    
    public function setDateFrom($date)
    {
        $this->date_from = $date;
    }
    
    public function setDateTo($date)
    {
        $this->date_to = $date;
    }
    
    public function setPayPal($paypal)
    {
        $this->paypal = $paypal;
    }
    
    public function setSumma($summa)
    {
        $this->summa = $summa;
    }
    
    public function setInvoiceNr($nr)
    {
        $this->invoice_number = $nr;
    }
    
    public function setPdf($pdf)
    {
        $this->pdf = $pdf;
    }
    
    public function setComment($comment)
    {
        $this->comment = $comment;
    }
    
    public function setPaid($paid)
    {
        $this->paid = $paid;
    }
    
    public function setSubmitedDate($date)
    {
        $this->submited_date = $date;
    }
    
    public function getSubmitedDate()
    {
        return $this->submited_date;
    }    
    
    public function setUid($uid)
    {
        $this->uid = $uid;
    }
    
    public function getPdf()
    {
        return $this->pdf;
    }
    
    public function __call($name, $arg)
    {
        return $this->$name;
    }

}
