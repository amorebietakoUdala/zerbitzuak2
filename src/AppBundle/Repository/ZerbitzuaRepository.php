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
//	    ->from('AppBundle\Entity\Zerbitzua', 'z')
	    ->leftJoin('AppBundle\Entity\Enpresa','e',
		\Doctrine\ORM\Query\Expr\Join::WITH,
		'qb.enpresa = e.id')
//	    ->andWhere('e.aktibatua = true')
	;
        if ( $criteria !== null )
        {
            foreach ( $criteria as $eremua => $filtroa ) {
		if ($eremua !== 'role' && $eremua !== 'locale' ) {
		    $qb->andWhere('qb.'.$eremua.' = :'.$eremua)
			->setParameter($eremua, $filtroa);
		}
            }
        }
	$qb->orderBy('e.ordena', 'ASC');
	$qb->addOrderBy('qb.ordena', 'ASC');
//	dump($qb->getQuery()->getResult(),$criteria, $qb);die;
        return $qb;
    }
    
    public function createZerbitzuAktiboakQueryBuilder()
    {
	$criteria = ['aktibatua' => true ];
	$qb = $this->createOrderedQueryBuilder($criteria);
        return $qb;
    }

}
