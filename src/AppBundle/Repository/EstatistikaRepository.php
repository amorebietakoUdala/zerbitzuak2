<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

/**
 * Description of EstatistikaRepository
 *
 * @author ibilbao
 */
class EstatistikaRepository extends EntityRepository {

    /**
    * @return \Doctrine\DBAL\Query\QueryBuilder
    */
    public function findAllOrderDescQB()
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.urtea', 'DESC');
    }

    public function findByCriteria(array $criteria, mixed $orderBy = null, $limit = null, $offset = null) {
	$criteria1 = $this->_remove_blank_filters($criteria);
	return parent::findBy($criteria1, $orderBy, $limit, $offset);
    }

    private function _remove_blank_filters ($criteria) {
	$new_criteria = [];
	foreach ($criteria as $key => $value) {
	    if (!empty($value))
		$new_criteria[$key] = $value;
	}
	return $new_criteria;
    }

//    /**
//    * @return \Doctrine\DBAL\Query\QueryBuilder
//    */
//    public function findDistinctUrteaQB()
//    {
//        return $this->createQueryBuilder('e')
//	    ->select(['e.urtea'], ['e.eskakizunak'])
//	    ->addGroupBy('e.urtea')
//            ->orderBy('e.urtea', 'DESC');
//	
////	    ->setMaxResults(10);
//	
//    }

}
