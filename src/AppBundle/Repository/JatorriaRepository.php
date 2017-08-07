<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

/**
 * Description of JatorriaRepository
 *
 * @author ibilbao
 */
class JatorriaRepository extends EntityRepository {
    
    /**
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    public function createOrderedQueryBuilder()
    {
        return $this->createQueryBuilder('jatorria')
            ->orderBy('jatorria.id', 'DESC');
    }
}
