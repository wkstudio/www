<?php

namespace Start\StartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
    private $error;
    private $templ_var;
    
    public function report_newAction()
    {
        $uid = $this->getUser()->getId();
        $em = $this->getDoctrine()->getEntityManager();
        $username = $this->getUser()->getUsername(); 
        $this->hideWordsOrHours();               
        if($this->get('request')->get('sbmt') && $this->validate())
        {
            $date = $this->get('request')->get('date');
            $minutes = $this->get('request')->get('hours') * 60 + $this->get('request')->get('minutes');
            $words = $this->get('request')->get('words');
            $content = $this->get('request')->get('content');
            $em->getRepository('StartStoreBundle:Report')->addReport($date, $minutes, $words, $content, $uid);
            $this->templ_var['allok'] = 'Report saved.';
            if($this->get('request')->get('sendmail') == 'on')
            {
                $this->sendEmailMessage($username, $date, $content);   
            }
            if($em->getRepository('StartStoreBundle:Report')->presentReportToday($uid))
            {
                $this->templ_var['presentReportToday'] = "1";
            }
            /*Add to log*/
            $em->getRepository('StartStoreBundle:Logging')->addToLog($this->getUser()->getId(), "Submitted a report");
            /*end Add to log*/ 
            return $this->render('StartStartBundle:Dashboard:new_report.html.twig', array('templ_var' => $this->templ_var));
        }        
        $this->storeTemplateVars();
        if($em->getRepository('StartStoreBundle:Report')->presentReportToday($uid))
        {
            $this->templ_var['presentReportToday'] = "1";
        }        
        return $this->render('StartStartBundle:Dashboard:new_report.html.twig', array('error' => $this->error, 
                                                                                        'templ_var' => $this->templ_var));        
    }

    private function sendEmailMessage($username, $date, $content)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Notification about report from user: ' . $username)
            ->setFrom($username)
            ->setTo('info@calmclinic.com')
            ->setBody($this->renderView(
                'StartStartBundle:Dashboard:email.html.twig',
                array('date' => $date, 'name' => $username, 'content' => $content)
            ), 'text/html');
        $this->get('mailer')->send($message);        
    }
        
    private function storeTemplateVars()
    {
        $this->templ_var['content'] = $this->get('request')->get('content');
        $this->templ_var['date'] = $this->get('request')->get('date');
        $this->templ_var['words'] = $this->get('request')->get('words');
        $this->templ_var['hours'] = $this->get('request')->get('hours');
        $this->templ_var['minutes'] = $this->get('request')->get('minutes');
    }
    
    private function hideWordsOrHours()
    {
        $uid = $this->getUser()->getId();
        $em = $this->getDoctrine()->getEntityManager();
        $userinfo = $em->getRepository('StartStoreBundle:User')->getUserInfo($uid);
        if($userinfo['pbw'] == '0')
        {
            $this->templ_var['hidewords'] = 1;   
        }
        if($userinfo['pbh'] == '0')
        {
            $this->templ_var['hidehours'] = 1;   
        }        
    }
    
    private function validate()
    {
        $date = $this->get('request')->get('date');
        $minutes = $this->get('request')->get('hours') * 60 + $this->get('request')->get('minutes');
        $words = $this->get('request')->get('words');
        $content = $this->get('request')->get('content');
     
        if($minutes == 0 && empty($words))
        {
            $this->error['subject'] = 'Parameter expected.';
        }
        if(empty($date))
        {
            $this->error['date'] = 'Choose date.';
        }
        if(empty($content))
        {
            $this->error['content'] = 'Fill the report.';
        }
        if(!$this->error)
        {
            return True;
        }
        else
        {
            return False;
        }
    }
}