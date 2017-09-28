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
	$qb = $this->createQueryBuilder('zerbitzua');
        
        if ( $criteria !== null )
        {
            foreach ( $criteria as $eremua => $filtroa ) {
		if ($eremua !== 'role' && $eremua !== 'locale' ) {
		    $qb->andWhere('zerbitzua.'.$eremua.' = :'.$eremua)
			->setParameter($eremua, $filtroa);
		}
            }
        }
	$qb->orderBy('zerbitzua.ordena', 'ASC');
        return $qb;
    }
}
