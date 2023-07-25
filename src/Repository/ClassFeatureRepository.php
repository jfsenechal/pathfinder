<?php

namespace AfmLibre\Pathfinder\Repository;

use AfmLibre\Pathfinder\Doctrine\OrmCrudTrait;
use AfmLibre\Pathfinder\Entity\ClassT;
use AfmLibre\Pathfinder\Entity\ClassFeature;
use AfmLibre\Pathfinder\Entity\Level;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ClassFeature|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClassFeature|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClassFeature[]    findAll()
 * @method ClassFeature[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClassFeatureRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, ClassFeature::class);
    }

    /**
     * @return ClassFeature[]
     */
    public function findByCharacterClass(ClassT $classT): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.classT = :val')
            ->setParameter('val', $classT)
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return ClassFeature[]
     */
    public function findByClassAndLevel(ClassT $classT, Level $level): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.classT = :val')
            ->setParameter('val', $classT)
            ->andWhere('c.level = :lvl')
            ->setParameter('lvl', $level)
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return ClassFeature[]
     */
    public function findByClassLevelAndSrc(ClassT $classT, Level $level, string $src): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.classT = :val')
            ->setParameter('val', $classT)
            ->andWhere('c.level = :lvl')
            ->setParameter('lvl', $level)
            ->andWhere('c.source = :src')
            ->setParameter('src', $src)
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
