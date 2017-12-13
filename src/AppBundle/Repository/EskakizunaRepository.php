<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Repository;

use AppBundle\Entity\Egoera;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityRepository;

/**
 * Description of EskakizunaRepository
 *
 * @author ibilbao
 */
class EskakizunaRepository extends EntityRepository {

     /**
     * @return QueryBuilder
     */
    public function createOrderedQueryBuilder()
    {
        return $this->createQueryBuilder('eskakizuna')
            ->orderBy('eskakizuna.id', 'DESC');
    }
    

    /**
    * @param type array $criteria
    * @return QueryBuilder
    */
    public function findAllLikeQueryBuilder($criteriaLike = null )
    {
        $qb = $this->createQueryBuilder('e');
        
        if ( $criteriaLike )
        {
            foreach ( $criteriaLike as $eremua => $filtroa ) {
                $qb->andWhere('e.'.$eremua.' LIKE :'.$eremua)
                    ->setParameter($eremua, '%'.$filtroa.'%');
            }
        }
	$qb->orderBy('e.noizInformatua', 'DESC');
        return $qb;
    }

    /**
    * @param type array $criteria
     *@param date form start_date of the search results
     *@param date form end_date of the search results
    * @return QueryBuilder
    */
    public function findAllFromToQB($criteriaAnd = null,  $criteriaLike = null, $from =null, $to =null )
    {
	$qb = $this->createQueryBuilder('e');
	if ($from !== null) {
	    $qb->andWhere('e.noizInformatua >= :from')
		->setParameter('from', $from);
	}
	if ($to !== null) {
	    $qb->andWhere('e.noizInformatua <= :to')
	    ->setParameter('to', $to);
	}
	if ( $criteriaAnd )
        {
            foreach ( $criteriaAnd as $eremua => $filtroa ) {
                $qb->andWhere('e.'.$eremua.' = :'.$eremua)
                    ->setParameter($eremua, $filtroa);
            }
        }
	if ( $criteriaLike )
        {
            foreach ( $criteriaLike as $eremua => $filtroa ) {
                $qb->andWhere('e.'.$eremua.' LIKE :'.$eremua)
                    ->setParameter($eremua, '%'.$filtroa.'%');
            }
        }
	$qb->orderBy('e.noizInformatua', 'DESC');
        return $qb;
    }

    
    /**
    * @param type array $criteria
    * @result Eskakizuna[]
    */
    public function findAllLike($criteriaLike = null )
    {
        return $this->findAllLikeQueryBuilder($criteriaLike)->getQuery()->getResult();
    }

    /**
    * @param type array $criteria
    * @result Eskakizuna[]
    */
    public function findAllOpen($criteria = null, $from =null, $to =null )
    {
	$criteriaLikeKeys = ['kalea' => null];
	$criteriaLike = $criteriaAnd = null;
	if ( $criteria !== null ) {
	    $criteriaLike = array_intersect_key($criteria,$criteriaLikeKeys);
	    $criteriaAnd = array_diff_key($criteria,$criteriaLikeKeys);
	}
	dump($this->findAllOpenQB($criteriaAnd, $criteriaLike, $from, $to)->getQuery()->getResult());die;
        return $this->findAllOpenQB($criteriaAnd, $criteriaLike, $from, $to)->getQuery()->getResult();
    }

        /**
    * @param type array $criteria
    * @return QueryBuilder
    */
    public function findAllOpenQB($criteriaAnd, $criteriaLike=null, $from=null, $to=null)
    {
	$qb = $this->findAllFromToQB($criteriaAnd, $criteriaLike, $from, $to);
	$qb->andWhere('e.egoera != :egoera')
	    ->setParameter('egoera', [Egoera::EGOERA_ITXIA]);
	$qb->orderBy('e.noizInformatua', 'DESC');
	return $qb;
    }

    
    
    public function findAllFromTo ($criteria = null, $from = null, $to = null )
    {
	$criteriaLikeKeys = ['kalea' => null];
	$criteriaLike = $criteriaAnd = null;
	if ( $criteria !== null ) {
	    $criteriaLike = array_intersect_key($criteria,$criteriaLikeKeys);
	    $criteriaAnd = array_diff_key($criteria,$criteriaLikeKeys);
	}
        return $this->findAllFromToQB($criteriaAnd, $criteriaLike ,$from,$to)->getQuery()->getResult();
    }

}
