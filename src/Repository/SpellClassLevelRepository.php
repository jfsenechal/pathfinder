<?php

namespace AfmLibre\Pathfinder\Repository;

use AfmLibre\Pathfinder\Entity\CharacterClass;
use AfmLibre\Pathfinder\Entity\Spell;
use AfmLibre\Pathfinder\Entity\SpellClassLevel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SpellClassLevel|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpellClassLevel|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpellClassLevel[]    findAll()
 * @method SpellClassLevel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpellClassLevelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, SpellClassLevel::class);
    }

    public function searchByClassAndSpell(?CharacterClass $class, Spell $spell): ?SpellClassLevel
    {
        return $this->createQueryBuilder('spellClassLevel')
            ->leftJoin('spellClassLevel.spell', 'spell', 'WITH')
            ->leftJoin('spellClassLevel.characterClass', 'characterClass', 'WITH')
            ->addSelect('characterClass', 'spell')
            ->andWhere('spell = :spell')
            ->setParameter('spell', $spell)
            ->andWhere('spellClassLevel.characterClass = :class2')
            ->setParameter('class2', $class)
            ->getQuery()->getOneOrNullResult();
    }

    /**
     * @param string|null $name
     * @param \AfmLibre\Pathfinder\Entity\CharacterClass|null $class
     * @return array|SpellClassLevel[]
     */
    public function searchByNameAndClass(?string $name = null, ?CharacterClass $class = null): array
    {
        $qb = $this->createQueryBuilder('spellClassLevel')
            ->leftJoin('spellClassLevel.spell', 'spell', 'WITH')
            ->leftJoin('spellClassLevel.characterClass', 'characterClass', 'WITH')
            ->addSelect('characterClass', 'spell');

        if ($name) {
            $qb->andWhere('spell.name LIKE :name')
                ->setParameter('name', '%'.$name.'%');
        }

        if ($class) {
            $qb->andWhere('spellClassLevel.characterClass = :class2')
                ->setParameter('class2', $class);
        }

        $qb->addOrderBy('spell.name');

        return $qb->getQuery()->getResult();
    }

    public function persist(SpellClassLevel $spellClassLevel)
    {
        $this->_em->persist($spellClassLevel);
    }

    public function flush()
    {
        $this->_em->flush();
    }
}
