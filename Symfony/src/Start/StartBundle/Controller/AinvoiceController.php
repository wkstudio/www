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
        empty($unpaid) ? $this->templ_var['unpaid'] = 'all' : $this->templ_var['unpaid'] = '0';
        
        $this->prepareInvoices();
        
        $this->templ_var['http_upload'] = $this->container->getParameter('uploadpath');
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
    
    private function prepareInvoices()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $this->templ_var['invoices'] = $em->getRepository('StartStoreBundle:Invoice')->getInvoices($this->templ_var['uid'], $this->templ_var['unpaid']);
    }
    

   
}