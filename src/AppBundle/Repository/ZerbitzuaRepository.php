<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;


/**
 * Description of ZerbitzuaRepository
 *
 * @author ibilbao
 */
class ZerbitzuaRepository extends EntityRepository {

    /**
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    public function createAlphabeticalQueryBuilder()
    {
        return $this->createQueryBuilder('zerbitzua')
            ->orderBy('zerbitzua.izena_eu', 'ASC');
    }

    /**
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    public function createOrderedQueryBuilder($criteria = null)
    {
	$qb = $this->createQueryBuilder('qb');
        $qb->select('qb')
	    ->from('AppBundle\Entity\Zerbitzua', 'z')
	    ->leftJoin('AppBundle\Entity\Enpresa','e',
		\Doctrine\ORM\Query\Expr\Join::WITH,
		'qb.enpresa = e.id'
        );
	
        if ( $criteria !== null )
        {
            foreach ( $criteria as $eremua => $filtroa ) {
		if ($eremua !== 'role' && $eremua !== 'locale' ) {
		    $qb->andWhere('z.'.$eremua.' = :'.$eremua)
			->setParameter($eremua, $filtroa);
		}
            }
        }
	$qb->orderBy('e.ordena', 'ASC');
	$qb->addOrderBy('qb.ordena', 'ASC');
        return $qb;
    }
}
