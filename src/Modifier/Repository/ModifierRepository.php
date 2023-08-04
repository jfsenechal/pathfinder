<?php

namespace AfmLibre\Pathfinder\Modifier\Repository;

use AfmLibre\Pathfinder\Doctrine\OrmCrudTrait;
use AfmLibre\Pathfinder\Entity\Modifier;
use AfmLibre\Pathfinder\Entity\Race;
use AfmLibre\Pathfinder\Entity\Skill;
use AfmLibre\Pathfinder\Modifier\ModifierListingEnum;
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
    public function findByAbility(ModifierListingEnum $ability): array
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

    public function findOneByIdClassNameAndAbility(?int $id, string $className, ModifierListingEnum $ability): ?Modifier
    {
        return $this->createQbl()
            ->andWhere('modifier.object_id = :id')
            ->setParameter('id', $id)
            ->andWhere('modifier.object_class = :class')
            ->setParameter('class', $className)
            ->andWhere('modifier.ability = :ability')
            ->setParameter('ability', $ability)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOneByIdClassNameAbilityAndRace(
        ?int $id,
        string $className,
        ModifierListingEnum $ability,
        Race $race
    ): ?Modifier {
        return $this->createQbl()
            ->andWhere('modifier.object_id = :id')
            ->setParameter('id', $id)
            ->andWhere('modifier.object_class = :className')
            ->setParameter('className', $className)
            ->andWhere('modifier.ability = :ability')
            ->setParameter('ability', $ability)
            ->andWhere('modifier.race = :race')
            ->setParameter('race', $race)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return Modifier[]
     */
    public function findSkillByRace(
        ModifierListingEnum $ability,
        Race $race
    ): array {
        return $this->createQbl()
            ->andWhere('modifier.object_class = :className')
            ->setParameter('className', Skill::class)
            ->andWhere('modifier.ability = :ability')
            ->setParameter('ability', $ability)
            ->andWhere('modifier.race = :race')
            ->setParameter('race', $race)
            ->getQuery()
            ->getResult();
    }

    private function createQbl(): QueryBuilder
    {
        return $this->createQueryBuilder('modifier')
            ->leftJoin('modifier.race', 'race', 'WITH')
            ->addSelect('race')
            ->orderBy('modifier.object_class', 'ASC');
    }

}
