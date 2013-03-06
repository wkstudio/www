<?php
namespace Start\StoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Start\StoreBundle\Entity\Report;

class ReportRepository extends EntityRepository
{
    public function addReport($date, $minutes, $words, $content, $uid)
    {
        $report = new Report();
        $report->setPostData(new \DateTime($date));        
        $report->setMinutes($minutes);
        $report->setWords($words);
        $report->setContent($content);
        $report->setUid($uid);                
        $em = $this->getEntityManager();
        $em->persist($report);
        $em->flush();                
    }
    
    public function getHoursWordsSummary($date_from, $date_to, $uid)
    {
        $summary = $this->getEntityManager()
            ->createQuery('SELECT SUM(r.words) as words, SUM(r.minutes) as minutes FROM StartStoreBundle:Report r 
                                                                                    WHERE r.uid = :id AND (r.postdata >= :date_from AND r.postdata <= :date_to)')
            ->setParameter('id', $uid)
            ->setParameter('date_from',new \DateTime($date_from))
            ->setParameter('date_to',new \DateTime($date_to))
            ->getResult();
        return $summary[0];        
    }
    
    public function getCountReports($userId)
    {
        $countReports = $this->getEntityManager()->createQuery('SELECT r  FROM StartStoreBundle:Report r WHERE r.uid = :uid')
                        ->setParameter("uid", $userId)
                        ->getResult();
        return count($countReports);
    }
    
    public function getReportsData($date_from, $date_to, $uid)
    {

            $reportsData = $this->getEntityManager()
            ->createQuery('SELECT r FROM StartStoreBundle:Report r  WHERE r.uid = :id AND (r.postdata >= :date_from AND r.postdata <= :date_to) ORDER BY r.postdata DESC')
            ->setParameter('id', $uid)
            ->setParameter('date_from',new \DateTime($date_from))
            ->setParameter('date_to',new \DateTime($date_to))
            ->getResult();            

        return $reportsData;        
    }  
    
    public function getReportsDataWithUsername($date_from, $date_to, $uid)
    {
        if($uid == 'all')
        {
            $reportsData = $this->getEntityManager()
            ->createQuery('SELECT r, u.first_name FROM StartStoreBundle:Report r LEFT JOIN StartStoreBundle:User u  WITH u.id = r.uid WHERE (r.postdata >= :date_from AND r.postdata <= :date_to)')
            ->setParameter('date_from',new \DateTime($date_from))
            ->setParameter('date_to',new \DateTime($date_to))
            ->getResult();            
        }
        else
        {
            $reportsData = $this->getEntityManager()
            ->createQuery('SELECT r, u.first_name FROM StartStoreBundle:Report r LEFT JOIN StartStoreBundle:User u  WITH u.id = r.uid WHERE r.uid = :id AND (r.postdata >= :date_from AND r.postdata <= :date_to)')
            ->setParameter('id', $uid)
            ->setParameter('date_from',new \DateTime($date_from))
            ->setParameter('date_to',new \DateTime($date_to))
            ->getResult();            
        }

        return $reportsData;        
    }
    
    public function updateReport($id, $minutes, $words, $content)
    {
        $this->getEntityManager()
        ->createQuery('UPDATE StartStoreBundle:Report r SET r.minutes = :minutes, r.words = :words, r.content = :content WHERE r.id = :id')
        ->setParameter('id', $id)
        ->setParameter('minutes', $minutes)
        ->setParameter('words', $words)
        ->setParameter('content', $content)
        ->getResult();
        
    }
    
    public function presentReportToday($uid)
    {
        $reportToday = $this->getEntityManager()
        ->createQuery('SELECT r FROM StartStoreBundle:Report r  WHERE r.uid = :uid AND r.postdata = :postdata')
        ->setParameter('uid', $uid)
        ->setParameter('postdata',new \DateTime(date('Y-m-d', time())))
        ->getResult();
        if(count($reportToday) > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function getReportById($id)
    {
        $report = $this->getEntityManager()
        ->createQuery('SELECT r FROM StartStoreBundle:Report r  WHERE r.id = :id')
        ->setParameter('id', $id)
        ->getResult();
        return $report[0];        
    }         
}