<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

/**
 * Description of EgoeraRepository
 *
 * @author ibilbao
 */
class EgoeraRepository extends EntityRepository {

     /**
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    public function createOrderedQueryBuilder()
    {
        return $this->createQueryBuilder('egoera')
            ->orderBy('egoera.id', 'DESC');
    }
}
