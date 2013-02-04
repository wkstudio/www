<?php
namespace Start\StoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

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
}