<?php

namespace Start\StartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
class StartController extends Controller
{
    public function indexAction()
    {
        $user = $this->getUser();
        $name = $user->getUsername();   
        return $this->render('StartStartBundle:Start:index.html.twig', array('name' => $name));

    }
    
    public function testAction()
    {
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $user = $this->getUser();
        $name = $user->getUsername(); 
        return $this->render('StartStartBundle:Start:index.html.twig', array('name' => $name));

    }    
    
    
}