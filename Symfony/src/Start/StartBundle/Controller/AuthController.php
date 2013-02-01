<?php

namespace Start\StartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

class AuthController extends Controller
{
    public function loginAction()
    {
        /*
         * The action's view can be rendered using render() method
         * or @Template annotation as demonstrated in DemoController.
         *
         */
        $request = $this->getRequest();
        $session = $request->getSession();

        // получить ошибки логина, если таковые имеются
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
        }
                 
                 //echo "!!!!!!!!!!!!!!!";
        $title = 'Login Form';
        return $this->render('StartStartBundle:Auth:login.html.twig', array('title' => $title, 'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error' => $error));
    }
 
}