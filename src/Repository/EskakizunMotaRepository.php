<?php

namespace App\Repository;

use App\Entity\EskakizunMota;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EskakizunMotaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EskakizunMota::class);
    }

     /**
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    public function createOrderedQueryBuilder()
    {
        return $this->createQueryBuilder('eskakizunMota')
            ->orderBy('eskakizunMota.id', 'DESC');
    }
}
