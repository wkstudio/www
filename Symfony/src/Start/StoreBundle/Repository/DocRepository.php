<?php
namespace Start\StoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Start\StoreBundle\Entity\Documentation;

class DocRepository extends EntityRepository
{
    public function getAllDocs()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT d FROM StartStoreBundle:Documentation d ')
            ->getResult();
    }
    
    public function getDocById($id)
    {
        $doc = $this->getEntityManager()
            ->createQuery('SELECT d FROM StartStoreBundle:Documentation d WHERE d.id = :id')
            ->setParameter('id', $id)
            ->getResult();
        return $doc[0];            
    }
    
    public function updateDocument($id, $title, $body)
    {
            $this->getEntityManager()
            ->createQuery('UPDATE StartStoreBundle:Documentation d SET d.doc_title = :title, d.doc_body = :body WHERE d.id = :id')
            ->setParameter('id', $id)
            ->setParameter('title', $title)
            ->setParameter('body', $body)
            ->getResult();        
    }
    
    public function addDocument($title, $body)
    {
        $document = new Documentation();
        $document->setTitle($title);
        $document->setBody($body);
        $em = $this->getEntityManager();
        $em->persist($document);
        $em->flush();
        return $document->id();                
    }    
    
    public function deleteDocById($id)
    {
            $this->getEntityManager()
            ->createQuery('DELETE FROM StartStoreBundle:Documentation d WHERE d.id = :id')
            ->setParameter('id', $id)
            ->getResult();        
    }    
}