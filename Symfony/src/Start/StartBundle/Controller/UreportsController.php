<?php

namespace Start\StartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UreportsController extends Controller
{
    private $templ_var = array();
    private $data = array();
    
    public function indexAction()
    {
        $id = $this->get('request')->get('repId');
        if(!empty($id))
        {
            $minutes = $this->get('request')->get('hours')*60 + $this->get('request')->get('minutes');
            $words = $this->get('request')->get('words');
            $content = $this->get('request')->get('content');
            $em = $this->getDoctrine()->getEntityManager(); 
            $em->getRepository('StartStoreBundle:Report')->updateReport($id, $minutes, $words, $content);            
        }
        $date_from  = $this->get('request')->get('date_from');
        $date_to  = $this->get('request')->get('date_to');
        empty($date_from) ? $this->templ_var['date_from'] = date('Y-m-'.'01', time()) : $this->templ_var['date_from'] = $date_from;
        empty($date_to) ? $this->templ_var['date_to'] = date('Y-m-d', time()) : $this->templ_var['date_to'] = $date_to;
        $this->getWordsHoursSummary();
        $this->getReportsData();
        $this->data['not_shown'] = $this->getNotShownReports();
        return $this->render('StartStartBundle:Ureports:index.html.twig', array('templ_var' => $this->templ_var, 'data' => $this->data ));        
    }
    
    private function getNotShownReports()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $countAllReports = $em->getRepository('StartStoreBundle:Report')->getCountReports($this->getUser()->getId());
        $countFilteredReports = count($this->templ_var['reports_data']);
       return $countAllReports - $countFilteredReports; 
    }
    
    private function getReportsData()
    {
        $this->templ_var['reports_data'] = array();
        $em = $this->getDoctrine()->getEntityManager(); 
        $this->templ_var['reports_data'] = $em->getRepository('StartStoreBundle:Report')->getReportsData($this->templ_var['date_from'], 
                                                                            $this->templ_var['date_to'], 
                                                                            $this->getUser()->getId());
        $this->checkLastReportForEdit();                                                                         
    }
    
    private function checkLastReportForEdit()
    {
        if(isset($this->templ_var['reports_data'][0]))
        {
            ob_start();
            print_r( $this->templ_var['reports_data'][0]);
            ob_get_clean();
            if(strtotime($this->templ_var['reports_data'][0]->getPostData()->date) < strtotime("-1 day", Time()))
            {
                $this->data['report_edit'] = 0;
            }
            else
            {
               $this->data['report_edit'] = 1; 
            }
        }        
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