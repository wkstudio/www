<?php

namespace Start\StartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DocController extends Controller
{
    private $error;
    
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
        if($this->get('request')->get('save-button') == null)
        {
            $em = $this->getDoctrine()->getEntityManager();
            $doc_item = $em->getRepository('StartStoreBundle:Documentation')
                        ->getDocById($this->get('request')->get('id'));
            $username = $this->getUser()->getUsername();  
            return $this->render('StartStartBundle:Doc:edit.html.twig', array('name' => $username, 'doc' => $doc_item));
        }
        else
        {
            if($this->validate())
            {
                $id = $this->get('request')->get('doc-id');
                $title = $this->get('request')->get('doc-title');
                $body = $this->get('request')->get('doc-body');    
                $em = $this->getDoctrine()->getEntityManager();
                $doc_item = $em->getRepository('StartStoreBundle:Documentation')
                            ->updateDocument($id, $title, $body);            
                return $this->redirect($this->generateUrl('doc_view')."?id=".$id);
            }
            else
            {
                $username = $this->getUser()->getUsername();
                $doc_item['id'] =  $this->get('request')->get('doc-id');
                $doc_item['doc_title'] =  $this->get('request')->get('doc-title');
                $doc_item['doc_body'] =  $this->get('request')->get('doc-body'); 
                return $this->render('StartStartBundle:Doc:edit.html.twig', array('name' => $username, 
                                                                                    'doc' => $doc_item, 
                                                                                    'error' => $this->error));                
            }
        }

    } 

    public function newAction()
    {
        if($this->get('request')->get('save-button') == null)
        {
            $doc['doc_title'] = '';
            $doc['doc_body'] = '';
            $username = $this->getUser()->getUsername();  
            return $this->render('StartStartBundle:Doc:new.html.twig', array('name' => $username, 'doc' => $doc));
        }
        else
        {
            if($this->validate())
            {
                $title = $this->get('request')->get('doc-title');
                $body = $this->get('request')->get('doc-body');    
                $em = $this->getDoctrine()->getEntityManager();
                $id = $em->getRepository('StartStoreBundle:Documentation')
                            ->addDocument($title, $body);            
                return $this->redirect($this->generateUrl('doc_view')."?id=".$id);
            }
            else
            {
                $username = $this->getUser()->getUsername();
                $doc_item['doc_title'] =  $this->get('request')->get('doc-title');
                $doc_item['doc_body'] =  $this->get('request')->get('doc-body'); 
                return $this->render('StartStartBundle:Doc:new.html.twig', array('name' => $username, 
                                                                                    'doc' => $doc_item, 
                                                                                    'error' => $this->error));                
            }
        }

    }

    public function deleteAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $doc_item = $em->getRepository('StartStoreBundle:Documentation')
                    ->deleteDocById($this->get('request')->get('id'));
        return $this->redirect($this->generateUrl('doc'));  
    }           
    
    private function validate()
    {
        if($this->get('request')->get('doc-title') == '')
        {
            $this->error = 'Empty title!!!'; 
            return false;  
        } 
    return true;
    }
}