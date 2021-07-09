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

    public function persist(CharacterClass $class)
    {
        $this->_em->persist($class);
    }

    public function flush()
    {
        $this->_em->flush();
    }

    public function getQl(): QueryBuilder
    {
        return $this->createQueryBuilder('character_class')
            ->orderBy('character_class.name');
    }
}
