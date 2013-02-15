<?php

namespace Start\StartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UreportsController extends Controller
{
    private $templ_var = array();
    
    public function indexAction()
    {
        $id = $this->get('request')->get('repId');
        if(!empty($id))
        {
            $minutes = $this->get('request')->get('hours')*60 + $this->get('request')->get('minutes');
            $words = $this->get('request')->get('words');
            $em = $this->getDoctrine()->getEntityManager(); 
            $em->getRepository('StartStoreBundle:Report')->updateReport($id, $minutes, $words);            
        }
        $date_from  = $this->get('request')->get('date_from');
        $date_to  = $this->get('request')->get('date_to');
        empty($date_from) ? $this->templ_var['date_from'] = date('Y-m-'.'01', time()) : $this->templ_var['date_from'] = $date_from;
        empty($date_to) ? $this->templ_var['date_to'] = date('Y-m-d', time()) : $this->templ_var['date_to'] = $date_to;
        $this->getWordsHoursSummary();
        $this->getReportsData();
        return $this->render('StartStartBundle:Ureports:index.html.twig', array('templ_var' => $this->templ_var ));        
    }
    
    private function getReportsData()
    {
        $em = $this->getDoctrine()->getEntityManager(); 
        $this->templ_var['reports_data'] = $em->getRepository('StartStoreBundle:Report')->getReportsData($this->templ_var['date_from'], 
                                                                            $this->templ_var['date_to'], 
                                                                            $this->getUser()->getId());
    }
    
    private function getWordsHoursSummary()
    {
        $em = $this->getDoctrine()->getEntityManager(); 
        $summary = $em->getRepository('StartStoreBundle:Report')->getHoursWordsSummary($this->templ_var['date_from'], 
                                                                            $this->templ_var['date_to'], 
                                                                            $this->getUser()->getId());
        $hours = floor($summary['minutes']/60);
        $minutes = $summary['minutes'] - $hours*60;        
        $this->templ_var['time'] = $hours . " h " . $minutes . " min";
        $this->templ_var['words'] = $summary['words'];        
    }  
}