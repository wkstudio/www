<?php

namespace Start\StartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AinvoiceController extends Controller
{
    private $templ_var = array();
    private $username;
    private $error;
    private $usercat;
    
    public function indexAction()
    {
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN'))
        {
            throw new AccessDeniedException();
        }
        $this->username = $this->getUser()->getUsername();
        $em = $this->getDoctrine()->getEntityManager();
        $this->templ_var['users'] = $em->getRepository('StartStoreBundle:User')->getAllUsers();        
        $uid = $this->get('request')->get('uid');
        $unpaid  = $this->get('request')->get('unpaid');
        
        empty($uid) ? $this->templ_var['uid'] = 'all' : $this->templ_var['uid'] = $uid;        
        $unpaid == 'on' || !$this->get('request')->get('sbmt') ? $this->templ_var['unpaid'] = '0' : $this->templ_var['unpaid'] = 'all';
        
        $this->prepareInvoices();
        

        return $this->render('StartStartBundle:Ainvoice:index.html.twig', array('error' => $this->error,
                                                                                 'templ_var' => $this->templ_var ));        
    }
    
    public function toggleAction()
    {
        $id = $this->get('request')->get('id');
        $flag = $this->get('request')->get('flag');
        echo $id."  -  ".$flag;
        $em = $this->getDoctrine()->getEntityManager();
        $em->getRepository('StartStoreBundle:Invoice')->setPaid($id, $flag);
        $response = new Response(json_encode(array('response' => 'ok')));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    
    public function downloadAction()
    {
        $DIR = $_SERVER['DOCUMENT_ROOT']."/Symfony/web/uploads/";
        $file = $DIR.htmlspecialchars($this->get('request')->get('q'));
        if(file_exists($file)) {
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment;filename="'.htmlspecialchars($this->get('request')->get('q')).'"');
            header('Content-length: '.filesize($file));
            header('Cache-Control: no-cache');
            header("Content-Transfer-Encoding: chunked"); 
         
            readfile($file);
            exit;
        } else {
            header("HTTP/1.0 404 Not Found");
        }
        return new Response();        
    }
    
    private function prepareInvoices()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $this->templ_var['invoices'] = $em->getRepository('StartStoreBundle:Invoice')->getInvoices($this->templ_var['uid'], $this->templ_var['unpaid']);
    }
    

   
}