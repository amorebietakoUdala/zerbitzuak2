<?php

namespace App\Repository;

use App\Entity\Eskatzailea;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EskatzaileaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Eskatzailea::class);
    }

    /**
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    public function createOrderedQueryBuilder()
    {
        return $this->createQueryBuilder('Eskatzailea')
            ->orderBy('eskatzailea.id', 'DESC');
    }
    
    /**
     * 
     * @param type $id
     * @result Eskatzailea
     */
    public function getEskatzaileaById($id)
    {
        $query = $this->createQueryBuilder('Eskatzailea')
            ->where('eskatzailea.id = :id')
            ->setParameter('id', $id)
            ->getQuery();
        $eskatzailea = $query->getFirstResult();
        return $eskatzailea;
    }

    /**
     * 
     * @param type $izena
     * @result Eskatzailea
     */
    public function getEskatzaileaByIzenaLike($izena)
    {
        $query = $this->createQueryBuilder('eskatzailea')
            ->where('eskatzailea.izena LIKE :izena')
            ->orderBy('eskatzailea.id', 'ASC')
            ->setParameter('izena', '%'.$izena.'%')
            ->getQuery();
        $eskatzailea = $query->getResult();
        
        return $eskatzailea;
    }

     /**
     * @param type array $criteria
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    public function findAllLikeQueryBuilder($criteria = null )
    {
        $qb = $this->createQueryBuilder('e');
        
        if ( $criteria )
        {
            foreach ( $criteria as $eremua => $filtroa ) {
                $qb->andWhere('e.'.$eremua.' LIKE :'.$eremua)
                    ->setParameter($eremua, '%'.$filtroa.'%');
            }
        }
        return $qb;
    }

     /**
     * @param type array $criteria
     * @result Eskatzailea[]
     */
    public function findAllLike($criteria = null )
    {
        return $this->findAllLikeQueryBuilder($criteria)->getQuery()->getResult();
    }
    
}
