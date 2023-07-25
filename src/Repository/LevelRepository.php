<?php

namespace AfmLibre\Pathfinder\Repository;

use AfmLibre\Pathfinder\Doctrine\OrmCrudTrait;
use AfmLibre\Pathfinder\Entity\ClassT;
use AfmLibre\Pathfinder\Entity\Level;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Level|null find($id, $lockMode = null, $lockVersion = null)
 * @method Level|null findOneBy(array $criteria, array $orderBy = null)
 * @method Level[]    findAll()
 * @method Level[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LevelRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Level::class);
    }

    public function findByClassAndLevel(ClassT $classT, int $level): ?Level
    {
        return $this->createQueryBuilder('level')
            ->andWhere('level.classT = :charclass')
            ->setParameter('charclass', $classT)
            ->andWhere('level.lvl = :lvl')
            ->setParameter('lvl', $level)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return Level[]
     */
    public function findByClass(ClassT $classT): array
    {
        return $this->createQueryBuilder('level')
            ->andWhere('level.classT = :charclass')
            ->setParameter('charclass', $classT)
            ->getQuery()
            ->getResult();
    }


}
