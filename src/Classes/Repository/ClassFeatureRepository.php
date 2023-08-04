<?php

namespace AfmLibre\Pathfinder\Classes\Repository;

use AfmLibre\Pathfinder\Doctrine\OrmCrudTrait;
use AfmLibre\Pathfinder\Entity\ClassFeature;
use AfmLibre\Pathfinder\Entity\ClassT;
use AfmLibre\Pathfinder\Entity\Level;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
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
            ->andWhere('class_feature.classT = :val')
            ->setParameter('val', $classT)
            ->orderBy('class_feature.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return ClassFeature[]
     */
    public function findByClassAndLevel(ClassT $classT, Level $level): array
    {
        return $this->createQbl()
            ->andWhere('class_feature.classT = :val')
            ->setParameter('val', $classT)
            ->andWhere('class_feature.level = :lvl')
            ->setParameter('lvl', $level)
            ->orderBy('class_feature.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return ClassFeature[]
     */
    public function findByClassLevelAndSrc(ClassT $classT, Level $level, string $src): array
    {
        return $this->createQbl()
            ->andWhere('class_feature.classT = :val')
            ->setParameter('val', $classT)
            ->andWhere('class_feature.level = :lvl')
            ->setParameter('lvl', $level)
            ->andWhere('class_feature.sourced = :src')
            ->setParameter('src', $src)
            ->orderBy('class_feature.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    private function createQbl(): QueryBuilder
    {
        return $this->createQueryBuilder('class_feature')
            ->leftJoin('class_feature.classT', 'classT', 'WITH')
            ->leftJoin('class_feature.level', 'level', 'WITH')
            ->addSelect('classT', 'level')
            ->addOrderBy('classT.name', 'ASC');
    }
}
