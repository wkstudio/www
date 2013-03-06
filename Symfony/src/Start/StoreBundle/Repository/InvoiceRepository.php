<?php
namespace Start\StoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Start\StoreBundle\Entity\Invoice;

class InvoiceRepository extends EntityRepository
{
    public function addInvoice($uid, $date_from, $date_to, $paypal, $sum, $invoice_nr, $fileLink, $comment)
    {
        $invoice = new Invoice();
        $invoice->setSubmitedDate(new \DateTime());        
        $invoice->setUid($uid);
        $invoice->setDateFrom(new \DateTime($date_from));
        $invoice->setDateTo(new \DateTime($date_to));
        $invoice->setPayPal($paypal);
        $invoice->setSumma($sum);
        $invoice->setInvoiceNr($invoice_nr);
        $invoice->setPdf($fileLink);
        $invoice->setComment($comment);
        $invoice->setPaid("0");                
        $em = $this->getEntityManager();
        $em->persist($invoice);
        $em->flush();                
    }
    
    public function getInvoices($uid, $paid = 'all')
    {
        if($uid == 'all')
        {
            if($paid == 'all')
            {
                $invoices = $this->getEntityManager()
                ->createQuery('SELECT i FROM StartStoreBundle:Invoice i ORDER BY i.submited_date DESC')
                ->getResult();
            }
            else
            {
                $invoices = $this->getEntityManager()
                ->createQuery('SELECT i FROM StartStoreBundle:Invoice i WHERE i.paid = :paid ORDER BY i.submited_date DESC')
                ->setParameter('paid', $paid)
                ->getResult();                
            }            
        }
        else
        {
            if($paid == 'all')
            {
                $invoices = $this->getEntityManager()
                ->createQuery('SELECT i FROM StartStoreBundle:Invoice i WHERE i.uid = :uid ORDER BY i.submited_date DESC')
                ->setParameter('uid', $uid)
                ->getResult();
            }
            else
            {
                $invoices = $this->getEntityManager()
                ->createQuery('SELECT i FROM StartStoreBundle:Invoice i WHERE i.uid = :uid AND i.paid = :paid ORDER BY i.submited_date DESC')
                ->setParameter('uid', $uid)
                ->setParameter('paid', $paid)
                ->getResult();            
            }
        }

        return $invoices;        
    }
    
   
    public function getInvoice($id)
    {
        $invoice = $this->getEntityManager()
            ->createQuery('SELECT i FROM StartStoreBundle:Invoice i WHERE i.id = :id')
            ->setParameter('id', $id)
            ->getResult();
        return $invoice[0];        
    }
    
    public function deleteInvoice($id)
    {
        $invoice = $this->getEntityManager()
            ->createQuery('DELETE FROM StartStoreBundle:Invoice i WHERE i.id = :id')
            ->setParameter('id', $id)
            ->getResult();        
    }
    public function setPaid($id, $flag)
    {
        $this->getEntityManager()
            ->createQuery('UPDATE StartStoreBundle:Invoice i SET i.paid = :paid WHERE i.id = :id')
            ->setParameter('id', $id)
            ->setParameter('paid', $flag)
            ->getResult();
    }  
}