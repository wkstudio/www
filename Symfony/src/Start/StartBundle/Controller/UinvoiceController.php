<?php

namespace Start\StartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UinvoiceController extends Controller
{
    private $templ_var = array();
    private $username;
    private $error;
    private $usercat;
    
    public function indexAction()
    {
        $this->username = $this->getUser()->getUsername();
        $em = $this->getDoctrine()->getEntityManager();
        $uid = $this->getUser()->getId();
        if($this->get('request')->get('sbmt') && $this->validate())
        {
             
            
            $fileLink = $this->writeFile();
            $date_from = $this->get('request')->get('date_from');
            $date_to = $this->get('request')->get('date_to');
            $paypal = $this->get('request')->get('paypal');
            $sum = $this->get('request')->get('sum');
            $invoice_nr = $this->get('request')->get('invoice_nr');
            $comment = $this->get('request')->get('comment');
            
            $em->getRepository('StartStoreBundle:Invoice')->addInvoice($uid, 
                                                                        $date_from, 
                                                                        $date_to, 
                                                                        $paypal, 
                                                                        $sum, 
                                                                        $invoice_nr, 
                                                                        $fileLink,
                                                                        $comment); 
            $this->cleanTemplateVars(); 
            $this->templ_var['success'] = 'Invoice saved.';          
        }
        else
        {
            $this->setTemplateVars();
        }
        
        $this->templ_var['invoices'] = $em->getRepository('StartStoreBundle:Invoice')->getInvoices($uid);

        $this->templ_var['http_upload'] = $this->container->getParameter('uploadpath');
        return $this->render('StartStartBundle:Uinvoice:index.html.twig', array('error' => $this->error,
                                                                                 'templ_var' => $this->templ_var ));        
    }
    
    public function deleteAction()
    {
        $id = $this->get('request')->get('id');
        $em = $this->getDoctrine()->getEntityManager();
        $invoice = $em->getRepository('StartStoreBundle:Invoice')->getInvoice($id);
        
        $upload_path = $this->get('kernel')->getRootDir() . '/../web/uploads/';
        $file = $upload_path.$invoice->getPdf();
        unlink($file);
        $em->getRepository('StartStoreBundle:Invoice')->deleteInvoice($id);        
        return $this->redirect($this->generateUrl('user_invoice'));
        
    }
    
    private function writeFile()
    {
        $path = $this->getUploadPath();
        $filename = md5(microtime()).".pdf";
        copy($_FILES["pdf"]["tmp_name"], $path.$filename);
        return $this->usercat.$filename;
    }
    
    private function validate()
    {
        
        $request = $this->get('request');
        $fl = explode('.', $_FILES["pdf"]["name"]);
        end($fl) == 'pdf' ? $pdf = true : $pdf = false;
        if($request->get('date_from') == '' || $request->get('date_to') == '')
        {
            $this->error['date'] = 'Date is mandatory';
        }
        if($request->get('paypal') == '')
        {
            $this->error['paypal'] = 'Paypal mandatory';
        }
        elseif(!filter_var($request->get('paypal'), FILTER_VALIDATE_EMAIL))
        {
           $this->error['paypal'] = 'Paypal not valid'; 
        } 
        if($request->get('sum') == '')
        {
            $this->error['sum'] = 'Sum empty';
        }
        elseif(!is_numeric(trim($request->get('sum'))))
        {
            $this->error['sum'] = 'Only numbers';
        }
        if($_FILES["pdf"]["name"] == '')
        {
            $this->error['pdf'] = 'Filename empty';
        }
        elseif(!$pdf)
        {
            $this->error['pdf'] = 'Only *.PDF';
        }        
        if($request->get('invoice_nr') == '')
        {
            $this->error['invoice_nr'] = 'Invoice Nr empty';
        }                       
        if($this->error)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    
    private function cleanTemplateVars()
    {
        $this->templ_var['date_from'] = '';
        $this->templ_var['date_to'] = '';
        $this->templ_var['paypal'] = '';
        $this->templ_var['sum'] = '';
        $this->templ_var['invoice_nr'] = '';
        $this->templ_var['pdf'] = '';
    }
    
    private function setTemplateVars()
    {
        $request = $this->get('request');
        $request->get('date_from') == '' ? $this->templ_var['date_from'] = '': $this->templ_var['date_from'] = $request->get('date_from');
        $request->get('date_to') == '' ? $this->templ_var['date_to'] = '': $this->templ_var['date_to'] = $request->get('date_to');
        $request->get('paypal') == '' ? $this->templ_var['paypal'] = '': $this->templ_var['paypal'] = $request->get('paypal');
        $request->get('sum') == '' ? $this->templ_var['sum'] = '': $this->templ_var['sum'] = $request->get('sum');
        $request->get('invoice_nr') == '' ? $this->templ_var['invoice_nr'] = '': $this->templ_var['invoice_nr'] = $request->get('invoice_nr');
        $request->get('pdf') == '' ? $this->templ_var['pdf'] = '': $this->templ_var['pdf'] = $request->get('pdf');
    }
    
    public function getUploadPath()
    {
        $this->usercat = str_replace('.', '', str_replace('@', '', $this->username))."/";
        $upload_path = $this->get('kernel')->getRootDir() . '/../web/uploads/' . $this->usercat;
        if(!file_exists($upload_path))
        {
            mkdir($upload_path, 0777, true);    
        }
        chmod(str_replace('app', 'web/uploads/', $this->get('kernel')->getRootDir()), 0777);
        return $upload_path;
    }    
}