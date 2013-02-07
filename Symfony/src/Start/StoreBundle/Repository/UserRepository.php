<?php
namespace Start\StoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Start\StoreBundle\Entity\User;
class UserRepository extends EntityRepository
{
    public function findAllOrderedByName()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT u FROM StartStoreBundle:User u ORDER BY u.username ASC')
            ->getResult();
    }
    
    public function getEnableUsers()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT u.id, u.username, ut.usertype FROM StartStoreBundle:User u LEFT JOIN StartStoreBundle:Usertype ut  WITH u.usertype = ut.id WHERE u.status = \'1\' ORDER BY ut.usertype')
            ->getResult();
    }
    
    public function getDisableUsers()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT u.id, u.username, ut.usertype FROM StartStoreBundle:User u LEFT JOIN StartStoreBundle:Usertype ut  WITH u.usertype = ut.id WHERE u.status = \'0\' ORDER BY ut.usertype')
            ->getResult();
    }
    
    public function getUserTypes()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT ut FROM StartStoreBundle:Usertype ut ORDER BY ut.usertype')
            ->getResult();
    }
    
    public function updateUser($request)
    {
        $this->getEntityManager()
            ->createQuery('UPDATE StartStoreBundle:User u 
                                SET u.username = :username, 
                                    u.first_name = :first_name, 
                                    u.phone = :phone, 
                                    u.fm_address = :fm_address, 
                                    u.country = :country, 
                                    u.contact_close = :contact_close, 
                                    u.timezone = :timezone, 
                                    u.usertype = :usertype,
                                    u.pbw_check = :pbw,
                                    u.pbw_daily = :pbw_dailytime,
                                    u.pbw_rate = :pbw_rate,
                                    u.pbh_daily = :pbh_dailytime,
                                    u.pbh_check = :pbh,
                                    u.pbh_rate = :pbh_rate WHERE u.id = :id')
            ->setParameter('id', $request->get('id'))
            ->setParameter('username', $request->get('username'))
            ->setParameter('first_name', $request->get('first_name'))
            ->setParameter('phone', $request->get('phone'))
            ->setParameter('fm_address', $request->get('fm_address'))
            ->setParameter('country', $request->get('country'))
            ->setParameter('contact_close', $request->get('contact_close'))
            ->setParameter('timezone', $request->get('timezone'))
            ->setParameter('usertype', $request->get('usertype'))
            ->setParameter('pbw', $request->get('pbw') == 'on'?'1':'0')
            ->setParameter('pbw_dailytime', $request->get('pbw_dailytime'))
            ->setParameter('pbw_rate', $request->get('pbw_rate'))
            ->setParameter('pbh_dailytime', $request->get('pbh_dailytime'))
            ->setParameter('pbh', $request->get('pbh') == 'on'?'1':'0')
            ->setParameter('pbh_rate', $request->get('pbh_rate'))
            ->getResult();        
    }
    
    public function setUserStatus($id, $status)
    {
        $this->getEntityManager()
            ->createQuery('UPDATE StartStoreBundle:User u 
                                SET u.status = :status WHERE u.id = :id')
            ->setParameter('id', $id)
            ->setParameter('status', $status)
            ->getResult();        
    }
    
    public function getUserInfo($id)
    {
        $userInfo = $this->getEntityManager()
            ->createQuery('SELECT u.id, 
                                    ut.usertype as usertype_text,
                                    u.username, 
                                    u.first_name, 
                                    u.phone, 
                                    u.fm_address, 
                                    u.country, 
                                    u.contact_close, 
                                    u.timezone, 
                                    u.usertype,
                                    u.status,
                                    u.pbw_check as pbw,
                                    u.pbw_daily as pbw_dailytime,
                                    u.pbw_rate,
                                    u.pbh_daily as pbh_dailytime,
                                    u.pbh_check as pbh,
                                    u.pbh_rate,
                                    u.signup_date FROM StartStoreBundle:User u LEFT JOIN StartStoreBundle:Usertype ut  WITH u.usertype = ut.id WHERE u.id = :id')
            ->setParameter('id', $id)
            ->getResult();
        return $userInfo[0];        
    }
    
    public function addUser($request)
    {
        $user = new User();
        $user->setFirstname($request->get('first_name'));
        $user->setUsername($request->get('username'));
        $user->setPassword(sha1($request->get('password')));
        $user->setTimezone($request->get('timezone'));
        $user->setPhone($request->get('phone'));
        $user->setFmAddress($request->get('fm_address'));
        $user->setCountry($request->get('country'));
        $user->setContactClose($request->get('contact_close'));
        $user->setPbwDaily($request->get('pbw_dailytime'));
        $user->setPbwRate($request->get('pbw_rate'));
        $user->setPbhDaily($request->get('pbh_dailytime'));
        $user->setPbhRate($request->get('pbh_rate'));        
        if($request->get('pbw') == 'on')
        {
            $user->setPbwCheck("1");
        }
        else
        {
            $user->setPbwCheck("0");  
        }
        if($request->get('pbh') == 'on')
        {
            $user->setPbhCheck("1");
        }
        else
        {
            $user->setPbhCheck("0");  
        }        
        
        $user->setStatus('1');
        $user->setRoles('ROLE_USER');
        $user->setUsertype($request->get('usertype'));
        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();   
    }
    
    public function isUserDuplicate($email)
    {
        $user = $this->getEntityManager()
                ->createQuery('SELECT u FROM StartStoreBundle:User u WHERE u.username = :email')
                ->setParameter('email', $email)
                ->getResult();
        if(empty($user))
        {
            return False;
        }                
        else
        {
            return True;
        }        
    }
    
    public function updateAccount($request)
    {
        $this->getEntityManager()
                ->createQuery('UPDATE StartStoreBundle:User u 
                                SET u.first_name = :first_name,
                                    u.username = :username,
                                    u.phone = :phone,
                                    u.fm_address = :fm_address,
                                    u.country = :country,
                                    u.contact_close = :contact_close WHERE u.id = :id')
                ->setParameter('first_name', $request->get('first_name'))
                ->setParameter('username', $request->get('username'))
                ->setParameter('phone', $request->get('phone'))
                ->setParameter('fm_address', $request->get('fm_address'))
                ->setParameter('country', $request->get('country'))
                ->setParameter('contact_close', $request->get('contact_close'))
                ->setParameter('id', $request->get('id'))
                ->getResult();        
    }
    
    public function isEmailDuplicate($id, $email)
    {
        $result = $this->getEntityManager()->createQuery('SELECT u.username FROM StartStoreBundle:User u WHERE (u.id <> :id AND u.username = :email)')
                                ->setParameter('id', $id)
                                ->setParameter('email', $email)
                                ->getResult();
        if(count($result) > 0)
        {
            return true;
        }
        else
        {
            return false;    
        }      
                                   
    }
 
}