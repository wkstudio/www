<?php

namespace Start\StartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    private $error;
    
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $users_enable = $em->getRepository('StartStoreBundle:User')
                    ->getEnableUsers();
        /*echo"<pre>";                    
        print_r($users_enable);                    
        echo"</pre>";*/            
        $users_disable = $em->getRepository('StartStoreBundle:User')
                    ->getDisableUsers();
        /*echo"<pre>";                    
        print_r($users_disable);                    
        echo"</pre>";*/                               
        $username = $this->getUser()->getUsername();
        return $this->render('StartStartBundle:User:index.html.twig', array('users_enable' => $users_enable,
                                                                           'users_disable' => $users_disable, 
                                                                           'name' => $username));

    }
    
    public function addAction()
    {
        if($this->get('request')->get('sbmt') == null)
        {
            $em = $this->getDoctrine()->getEntityManager();
            $usertypes = $em->getRepository('StartStoreBundle:Usertype')
                        ->getUserTypes();
            $inp = $this->storeTemplateVars();                   
            $username = $this->getUser()->getUsername();
            return $this->render('StartStartBundle:User:add.html.twig', array('inp' => $inp, 'name' => $username, 'usertypes' => $usertypes));
        }
        else
        {
            if($this->addValidate())
            {
                $em = $this->getDoctrine()->getEntityManager();
                $em->getRepository('StartStoreBundle:User')
                            ->addUser($this->get('request'));                
                return $this->redirect($this->generateUrl('user_index'));
            }
            else
            {
                $inp = $this->storeTemplateVars();
                $em = $this->getDoctrine()->getEntityManager();
                $usertypes = $em->getRepository('StartStoreBundle:Usertype')
                            ->getUserTypes();
                                    
                $username = $this->getUser()->getUsername();
                return $this->render('StartStartBundle:User:add.html.twig', array('inp' => $inp, 'name' => $username, 'usertypes' => $usertypes, 'error' => $this->error));                
            }
        }        
    }
    
    public function editAction()
    {
        if($this->get('request')->get('sbmt') == null)
        {
            $em = $this->getDoctrine()->getEntityManager();
            $usertypes = $em->getRepository('StartStoreBundle:Usertype')
                        ->getUserTypes();
            $inp = $em->getRepository('StartStoreBundle:User')->getUserInfo($this->get('request')->get('id'));
            $username = $this->getUser()->getUsername();        
            return $this->render('StartStartBundle:User:edit.html.twig', array('inp' => $inp, 'name' => $username, 'usertypes' => $usertypes, 'error' => $this->error));
        }
        else
        {
            if($this->editValidate())
            {
                $em = $this->getDoctrine()->getEntityManager();
                $em->getRepository('StartStoreBundle:User')
                            ->updateUser($this->get('request'));                
                return $this->redirect($this->generateUrl('user_index')); 
            }
            else
            {
                $em = $this->getDoctrine()->getEntityManager();
                $usertypes = $em->getRepository('StartStoreBundle:Usertype')
                            ->getUserTypes();
                $inp = $this->storeTemplateVars();
                $username = $this->getUser()->getUsername();        
                return $this->render('StartStartBundle:User:edit.html.twig', array('inp' => $inp, 'name' => $username, 'usertypes' => $usertypes, 'error' => $this->error));                
            }
            
        }                            
    }
    
    public function switchAction()
    {
        $userId = $this->get('request')->get('id');
        $em = $this->getDoctrine()->getEntityManager();
        $userInfo = $em->getRepository('StartStoreBundle:User')
                    ->getUserInfo($userId);
        if($userInfo['status'] == '1')
        {
            $em->getRepository('StartStoreBundle:User')->setUserStatus($userId, '0');            
        }                            
        else
        {
            $em->getRepository('StartStoreBundle:User')->setUserStatus($userId, '1');    
        }
        return $this->redirect($this->generateUrl('user_index'));
    }
    
    private function editValidate()
    {
        if(!$this->get('request')->get('first_name'))
        {
            $this->error['first_name'] = 'Empty name';
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
    
    private function addValidate()
    {
        $email = $this->get('request')->get('username');
        if(!$this->get('request')->get('first_name'))
        {
            $this->error['first_name'] = 'Empty name';
        }
        if(!$email)
        {
            $this->error['email'] = 'Empty Email';
        } 
        else
        {
            $em = $this->getDoctrine()->getEntityManager();
            $isUserDuplicate = $em->getRepository('StartStoreBundle:User')
                        ->isUserDuplicate($email);
            if($isUserDuplicate)
            {
                $this->error['email'] = 'User already exist!!!';    
            }            
        }
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $this->error['invalid_email'] = "Email is invalid";
        }
        if(!$this->get('request')->get('password'))
        {
            $this->error['password'] = 'Empty password';
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
    
    private function storeTemplateVars()
    {
        $inp['id'] = $this->get('request')->get('id');
        $inp['first_name'] = $this->get('request')->get('first_name');
        $inp['username'] = $this->get('request')->get('username');
        $inp['password'] = $this->get('request')->get('password');
        $inp['phone'] = $this->get('request')->get('phone');
        $inp['fm_address'] = $this->get('request')->get('fm_address');
        $inp['country'] = $this->get('request')->get('country');
        $inp['contact_close'] = $this->get('request')->get('contact_close');
        $inp['timezone'] = $this->get('request')->get('timezone');
        $inp['usertype'] = $this->get('request')->get('usertype');
        $inp['pbw_dailytime'] = $this->get('request')->get('pbw_dailytime');
        $inp['pbw_rate'] = $this->get('request')->get('pbw_rate');
        $inp['pbh_dailytime'] = $this->get('request')->get('pbh_dailytime');
        $inp['pbh_rate'] = $this->get('request')->get('pbh_rate');
        $inp['pbw'] = $this->get('request')->get('pbw');
        $inp['pbh'] = $this->get('request')->get('pbh');
        return $inp;
    }
}