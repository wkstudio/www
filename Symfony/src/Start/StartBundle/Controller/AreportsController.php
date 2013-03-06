<?php

namespace Start\StartBundle\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AreportsController extends Controller
{
    private $templ_var = array();
    
    public function indexAction()
    {
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN'))
        {
            throw new AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();
        $this->templ_var['users'] = $em->getRepository('StartStoreBundle:User')->getAllUsers();
        
        $day = 60*60*24;
        $date_from  = $this->get('request')->get('date_from');
        $date_to  = $this->get('request')->get('date_to');
        $uid = $this->get('request')->get('uid');
        
        empty($date_from) ? $this->templ_var['date_from'] = date('Y-m-d', time()-3*$day) : $this->templ_var['date_from'] = $date_from;
        empty($date_to) ? $this->templ_var['date_to'] = date('Y-m-d', time()) : $this->templ_var['date_to'] = $date_to;
        empty($uid) ? $this->templ_var['uid'] = 'all' : $this->templ_var['uid'] = $uid;
        
        $this->prepareReports();
                                                                                         
        return $this->render('StartStartBundle:Areports:index.html.twig', array('templ_var' => $this->templ_var ));        
    }
    
    public function getreportAction()
    {
        $id = $this->get('request')->get('id');
        $em = $this->getDoctrine()->getEntityManager();
        $report = $em->getRepository('StartStoreBundle:Report')->getReportById($id);
        $rep['id'] = $id; 
        $rep['content'] = $report->getContent();
        $rep['words'] = $report->getWords();
        $rep['minutes'] = $report->getMinutes();
        $response = new Response(json_encode($rep));
        $response->headers->set('Content-Type', 'application/json');
        return $response;       
    }
    
    public function updatereportAction()
    {
        $request = $this->get('request');
        $id = $request->get('id');
        $minutes = $request->get('minutes');
        $words = $request->get('words');
        $content = $request->get('content');
        $em = $this->getDoctrine()->getEntityManager();
        $em->getRepository('StartStoreBundle:Report')->updateReport($id, $minutes, $words, $content);
        return new Response();
    }
    
    private function prepareReports()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $reports = $em->getRepository('StartStoreBundle:Report')->getReportsDataWithUsername($this->templ_var['date_from'],
                                                                                                    $this->templ_var['date_to'],
                                                                                                   $this->templ_var['uid']);
        $this->templ_var['reports'] = array();                                                                                         
        $i = 0;                                                                                                    
        foreach($reports as $report)
        {
            ob_start();
            print_r($report[0]);
            ob_get_clean();
            $this->templ_var['reports'][$i]['id'] = $report[0]->getId();
            $this->templ_var['reports'][$i]['data'] = date("Y-m-d l", strtotime($report[0]->getPostData()->date));
            $this->templ_var['reports'][$i]['name'] = $report['first_name'];
            $this->templ_var['reports'][$i]['hours'] = round($report[0]->getMinutes()/60, 2);
            $this->templ_var['reports'][$i]['words'] = $report[0]->getWords();
            $this->templ_var['reports'][$i]['content'] = $report[0]->getCutContent(5000);
            $i++;
        }        
    }

}