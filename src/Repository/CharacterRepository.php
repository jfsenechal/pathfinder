<?php

namespace AfmLibre\Pathfinder\Repository;

use AfmLibre\Pathfinder\Entity\Character;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Character|null find($id, $lockMode = null, $lockVersion = null)
 * @method Character|null findOneBy(array $criteria, array $orderBy = null)
 * @method Character[]    findAll()
 * @method Character[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CharacterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Character::class);
    }

    public function persist(Character $character)
    {
        $this->_em->persist($character);
    }

    public function flush()
    {
        $this->_em->flush();
    }

    /**
     * @param string|null $name
     * @return array|Character[]
     */
    public function searchByUser(?string $name = null): array
    {
        $qb = $this->createQueryBuilder('character')
            ->leftJoin('character.characterClass', 'characterClass', 'WITH')
            ->leftJoin('character.race', 'race', 'WITH')
            ->addSelect('characterClass', 'race');

        if ($name) {
            $qb->andWhere('character.name LIKE :name')
                ->setParameter('name', '%'.$name.'%');
        }

        $qb->addOrderBy('character.name');

        return $qb->getQuery()->getResult();
    }
}
