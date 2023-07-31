<?php

namespace AfmLibre\Pathfinder\Repository;

use AfmLibre\Pathfinder\Doctrine\OrmCrudTrait;
use AfmLibre\Pathfinder\Entity\Modifier;
use AfmLibre\Pathfinder\Modifier\ModifierEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Modifier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Modifier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Modifier[]    findAll()
 * @method Modifier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModifierRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Modifier::class);
    }

    /**
     * @return Modifier[]
     */
    public function findAllOrdered(): array
    {
        return $this->createQbl()
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Modifier[]
     */
    public function findByAbility(ModifierEnum $ability): array
    {
        return $this->createQbl()
            ->andWhere('modifier.ability = :ability')
            ->setParameter('ability', $ability)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Modifier[]
     */
    public function findByClassName(string $className): array
    {
        return $this->createQbl()
            ->andWhere('modifier.object_class = :class')
            ->setParameter('class', $className)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Modifier[]
     */
    public function findByIdAndClassName(int $id, string $className): array
    {
        return $this->createQbl()
            ->andWhere('modifier.object_id = :id')
            ->setParameter('id', $id)
            ->andWhere('modifier.object_class = :class')
            ->setParameter('class', $className)
            ->getQuery()
            ->getResult();
    }

    public function findOneByIdClassNameAndAbility(?int $id, string $className, ModifierEnum $ability): ?Modifier
    {
        return $this->createQbl()
            ->andWhere('modifier.object_id = :id')
            ->setParameter('id', $id)
            ->andWhere('modifier.object_class = :class')
            ->setParameter('class', $className)
            ->andWhere('modifier.object_class = :ability')
            ->setParameter('ability', $ability)
            ->getQuery()
            ->getOneOrNullResult();
    }

    private function createQbl(): QueryBuilder
    {
        return $this->createQueryBuilder('modifier')
            ->orderBy('modifier.object_class', 'ASC');
    }

}
