<?php

namespace Start\StartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AccountController extends Controller
{
    private $error;
    private $data;
    
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $userinfo = $em->getRepository('StartStoreBundle:User')->getUserInfo($this->getUser()->getId());
        return $this->render('StartStartBundle:Account:index.html.twig', array('userinfo' => $userinfo));        
    }
    
    public function updateAction()
    {
        $request = $this->get('request');
        $id = $request->get('id');
        $email = $request->get('username');
        $email_confirm = $request->get('username_confirm');
        $em = $this->getDoctrine()->getEntityManager();
        if($this->validateEmail($id, $email, $email_confirm, $em))
        {
            $em->getRepository('StartStoreBundle:User')->updateAccount($request);
            $this->data['success'] = "Account updated successfully";
            /*Add to log*/
            $em->getRepository('StartStoreBundle:Logging')->addToLog($this->getUser()->getId(), "Account: updated details");
            /*end Add to log*/            
        }               
        $userinfo = $em->getRepository('StartStoreBundle:User')->getUserInfo($this->getUser()->getId());

        return $this->render('StartStartBundle:Account:index.html.twig', array('userinfo' => $userinfo,
                                                                                'error' => $this->error,
                                                                                'data' => $this->data));        
    }
    
    public function password_updateAction()
    {
        $uid = $this->getUser()->getId();
        $em = $this->getDoctrine()->getEntityManager();

        if($this->validatePassword($uid, $em))
        {
            $em->getRepository('StartStoreBundle:User')->updatePassword($uid, $this->get('request')->get('new_password'));
            $this->error['ok'] = 'Password Changed';
            $this->data['success'] = "Password updated successfully";  
            /*Add to log*/
            $em->getRepository('StartStoreBundle:Logging')->addToLog($this->getUser()->getId(), "Account: Changed password");
            /*end Add to log*/              
        }
        $userinfo = $em->getRepository('StartStoreBundle:User')->getUserInfo($uid); 
               
        return $this->render('StartStartBundle:Account:index.html.twig', array('userinfo' => $userinfo,
                                                                                'error' => $this->error,
                                                                                'data' => $this->data));        
    }
    
    private function isNewPasswordsIdentical()
    {
        if($this->get('request')->get('new_password') == $this->get('request')->get('repeat_password'))
        {
            return True;
        }
        else
        {
            return False;
        }
    }
    
    private function validateEmail($id, $email, $email_confirm, $em)
    {
        $isDuplicate = $em->getRepository('StartStoreBundle:User')->isEmailDuplicate($id, $email);
        if($isDuplicate)
        {
            $this->error['duplicate'] = "Email/Login already exists!!!";
        }
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $this->error['invalid_email'] = "Email is invalid";
        }
        if($email != $email_confirm)
        {
            $this->error['confirm_email'] = "Emails are not identical";
        }
        if($this->error)
        {
            return False;
        }
        else
        {
            return True;
        }        
    }
    
    private function validatePassword($uid, $em)
    {
        $isPasswordCorrect = $em->getRepository('StartStoreBundle:User')
                                ->isPasswordCorrect($uid, $this->get('request')->get('current_password'));
        if(!$isPasswordCorrect)
        {
            $this->error['incorrect_password'] = 'Incorrect password';
        }
        if(!$this->isNewPasswordsIdentical())
        {
            $this->error['identical'] = 'Passwords are not identical';
        }
        if($this->error)
        {
            return False;
        }        
        else
        {
            return True;
        }
    }
}