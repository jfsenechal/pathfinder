<?php

namespace AfmLibre\Pathfinder\Repository;

use AfmLibre\Pathfinder\Doctrine\OrmCrudTrait;
use AfmLibre\Pathfinder\Entity\Race;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Race|null find($id, $lockMode = null, $lockVersion = null)
 * @method Race|null findOneBy(array $criteria, array $orderBy = null)
 * @method Race[]    findAll()
 * @method Race[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RaceTraitRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Race::class);
    }

    // /**
    //  * @return RaceTrait[] Returns an array of RaceTrait objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RaceTrait
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
