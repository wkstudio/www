<?php

namespace Start\StartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AccountController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $userinfo = $em->getRepository('StartStoreBundle:User')->getUserInfo($this->getUser()->getId());
        if($this->get('request')->get('error') != null)
        {
            $error['email'] = 'User exists!!!';
        }
        else
        {
            $error = "";
        }
        $username = $this->getUser()->getUsername();
        return $this->render('StartStartBundle:Account:index.html.twig', array('name' => $username, 
                                                                                'userinfo' => $userinfo, 
                                                                                'error' => $error));        
    }
    
    public function updateAction()
    {
        $request = $this->get('request');
        $id = $request->get('id');
        $email = $request->get('username');
        $em = $this->getDoctrine()->getEntityManager();
        $isDuplicate = $em->getRepository('StartStoreBundle:User')->isEmailDuplicate($id, $email);
        if(!$isDuplicate)
        {
            $userinfo = $em->getRepository('StartStoreBundle:User')->updateAccount($request);
            return $this->redirect($this->generateUrl('account'));
        }
        else
        {
            return $this->redirect($this->generateUrl('account')."?error=1");    
        }        
        
    }
    
    public function password_updateAction()
    {
        return $this->redirect($this->generateUrl('account'));        
    }
}