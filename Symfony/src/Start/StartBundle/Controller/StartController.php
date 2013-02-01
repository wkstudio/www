<?php

namespace Start\StartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class StartController extends Controller
{
    public function indexAction()
    {
        /*
         * The action's view can be rendered using render() method
         * or @Template annotation as demonstrated in DemoController.
         *
         */
                 //echo "!!!!!!!!!!!!!!!";
        $user = $this->getUser();
        $name = $user->getUsername();   
        //print_r(get_class_methods ($user));
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