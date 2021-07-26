<?php

namespace AfmLibre\Pathfinder\Repository;

use AfmLibre\Pathfinder\Entity\CharacterClass;
use AfmLibre\Pathfinder\Entity\ClassFeature;
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
    public function findByCharacterClass(CharacterClass $characterClass)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.character_class = :val')
            ->setParameter('val', $characterClass)
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
