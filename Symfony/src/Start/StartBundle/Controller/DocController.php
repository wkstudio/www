<?php

namespace Start\StartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DocController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $doc_items = $em->getRepository('StartStoreBundle:Documentation')
                    ->getAllDocs();
                    
        $username = $this->getUser()->getUsername();
        return $this->render('StartStartBundle:Doc:index.html.twig', array('doc_items' => $doc_items, 'name' => $username));

    }
    
    public function viewAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $doc_item = $em->getRepository('StartStoreBundle:Documentation')
                    ->getDocById($this->get('request')->get('id'));
        $username = $this->getUser()->getUsername();  
        return $this->render('StartStartBundle:Doc:view.html.twig', array('name' => $username, 'doc' => $doc_item));

    }
    
    public function editAction()
    {
        if(!$this->get('request')->get('editor1')){
            $em = $this->getDoctrine()->getEntityManager();
            $doc_item = $em->getRepository('StartStoreBundle:Documentation')
                        ->getDocById($this->get('request')->get('id'));
            $username = $this->getUser()->getUsername();  
            return $this->render('StartStartBundle:Doc:edit.html.twig', array('name' => $username, 'doc' => $doc_item));
        }
        else{
            return $this->redirect($this->generateUrl('doc'));
        }

    }        
}