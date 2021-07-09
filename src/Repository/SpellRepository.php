<?php

namespace AfmLibre\Pathfinder\Repository;

use AfmLibre\Pathfinder\Entity\CharacterClass;
use AfmLibre\Pathfinder\Entity\Spell;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Spell|null find($id, $lockMode = null, $lockVersion = null)
 * @method Spell|null findOneBy(array $criteria, array $orderBy = null)
 * @method Spell[]    findAll()
 * @method Spell[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpellRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Spell::class);
    }

    public function persist(Spell $spell)
    {
        $this->_em->persist($spell);
    }

    public function flush()
    {
        $this->_em->flush();
    }

    /**
     * @param string|null $name
     * @param \AfmLibre\Pathfinder\Entity\CharacterClass|null $class
     * @return array|Spell[]
     */
    public function searchByNameAndClass(?string $name = null, ?CharacterClass $class = null): array
    {
        $qb = $this->createQueryBuilder('spell')
            ->leftJoin('spell.spell_classes', 'spell_classes', 'WITH')
            ->addSelect('spell_classes');

        if ($name) {
            $qb->andWhere('spell.name LIKE :name')
                ->setParameter('name', '%'.$name.'%');
        }

        if ($class) {
            $qb->andWhere('spell_classes.characterClass = :class2')
                ->setParameter('class2', $class);
        }

        $qb->addOrderBy('spell.name');

        return $qb->getQuery()->getResult();

    }
}
