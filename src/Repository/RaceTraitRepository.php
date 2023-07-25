<?php

namespace AfmLibre\Pathfinder\Repository;

use AfmLibre\Pathfinder\Doctrine\OrmCrudTrait;
use AfmLibre\Pathfinder\Entity\Race;
use AfmLibre\Pathfinder\Entity\RaceTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RaceTrait|null find($id, $lockMode = null, $lockVersion = null)
 * @method RaceTrait|null findOneBy(array $criteria, array $orderBy = null)
 * @method RaceTrait[]    findAll()
 * @method RaceTrait[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RaceTraitRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, RaceTrait::class);
    }

    /**
     * @return RaceTrait[]
     */
    public function findByRace(Race $race): array
    {
        return $this->createQueryBuilder('race_trait')
            ->andWhere('race_trait.race = :race')
            ->setParameter('race', $race)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return RaceTrait[]
     */
    public function findByRaceAndName(Race $race, string $name): array
    {
        return $this->createQueryBuilder('race_trait')
            ->andWhere('race_trait.race = :race')
            ->setParameter('race', $race)
            ->andWhere('race_trait.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getResult();
    }
}
