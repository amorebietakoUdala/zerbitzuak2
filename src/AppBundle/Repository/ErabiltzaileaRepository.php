<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Description of ErabiltzaileaRepository
 *
 * @author ibilbao
 */
class ErabiltzaileaRepository extends EntityRepository {

     /**
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    public function findAllOrderedByOrdena()
    {
        return $this->createQueryBuilder('erabiltzailea')
            ->orderBy('erabiltzailea.ordena', 'DESC');
    }
    
     /**
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    public function findArduradunakQueryBuilder($enpresa)
    {
        return $this->createQueryBuilder('arduraduna')
            ->where('arduraduna.roles LIKE :role')
            ->andWhere('arduraduna.enpresa = :enpresa')
            ->setParameter('role', '%ROLE_ARDURADUNA%')
            ->setParameter('enpresa', $enpresa )
            ->orderBy('arduraduna.id', 'DESC');
    }

     /**
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    public function findAllArduradunakQueryBuilder()
    {
        return $this->createQueryBuilder('arduraduna')
            ->where('arduraduna.roles LIKE :role')
            ->setParameter('role', '%ROLE_ARDURADUNA%')
            ->orderBy('arduraduna.id', 'DESC');
    }
    
    /**
    * @param string $role
    *
    * @return array
    */
   public function findByRole($role)
   {
       $qb = $this->_em->createQueryBuilder();
       $qb->select('u')
	   ->from($this->_entityName, 'u')
	   ->where('u.roles LIKE :roles')
	   ->setParameter('roles', '%"'.$role.'"%')
	   ->andWhere('u.enabled = true');

       return $qb->getQuery()->getResult();
   }

}
