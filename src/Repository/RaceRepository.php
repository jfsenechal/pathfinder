<?php

namespace AfmLibre\Pathfinder\Repository;

use AfmLibre\Pathfinder\Doctrine\OrmCrudTrait;
use AfmLibre\Pathfinder\Entity\Race;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Race|null find($id, $lockMode = null, $lockVersion = null)
 * @method Race|null findOneBy(array $criteria, array $orderBy = null)
 * @method Race[]    findAll()
 * @method Race[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RaceRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Race::class);
    }

    public function getQl(): QueryBuilder
    {
        return $this->createQbl();
    }

    /**
     * @return array|Race[]
     */
    public function findAllOrdered(): array
    {
        return $this->createQbl()
            ->getQuery()
            ->getResult();
    }

    public function findOneByName(string $name): ?Race
    {
        return $this->createQbl()
            ->andWhere('race.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }

    private function createQbl(): QueryBuilder
    {
        return $this->createQueryBuilder('race')
            ->orderBy('race.name', 'ASC');
    }

}
