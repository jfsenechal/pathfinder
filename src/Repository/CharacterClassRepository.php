<?php

namespace AfmLibre\Pathfinder\Repository;

use AfmLibre\Pathfinder\Entity\CharacterClass;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CharacterClass|null find($id, $lockMode = null, $lockVersion = null)
 * @method CharacterClass|null findOneBy(array $criteria, array $orderBy = null)
 * @method CharacterClass[]    findAll()
 * @method CharacterClass[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CharacterClassRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, CharacterClass::class);
    }

    public function findByShortName(string $name): ?CharacterClass
    {
        return $this->createQueryBuilder('character_class')
            ->andWhere('character_class.short_name = :name')
            ->setParameter('name', $name)
            ->getQuery()->getOneOrNullResult();
    }

    public function findByName(string $name): ?CharacterClass
    {
        return $this->createQueryBuilder('character_class')
            ->andWhere('character_class.name = :name')
            ->setParameter('name', $name)
            ->getQuery()->getOneOrNullResult();
    }

    public function getQl(): QueryBuilder
    {
        return $this->createQueryBuilder('character_class')
            ->orderBy('character_class.name');
    }

    /**
     * @return array|CharacterClass[]
     */
    public function searchByName(?string $name = null): array
    {
        $qb = $this->createQueryBuilder('character_class');
        if ($name) {
            $qb->andWhere('character_class.name LIKE :name')
                ->setParameter('name', '%'.$name.'%');
        }
        $qb->orderBy('character_class.name');

        return $qb->getQuery()->getResult();
    }
}
