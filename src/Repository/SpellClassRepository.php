<?php

namespace AfmLibre\Pathfinder\Repository;

use AfmLibre\Pathfinder\Entity\CharacterClass;
use AfmLibre\Pathfinder\Entity\Spell;
use AfmLibre\Pathfinder\Entity\SpellClass;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SpellClass|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpellClass|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpellClass[]    findAll()
 * @method SpellClass[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpellClassRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, SpellClass::class);
    }

    public function searchByClassAndSpell(?CharacterClass $class, Spell $spell): ?SpellClass
    {
        return $this->createQueryBuilder('spellClass')
            ->leftJoin('spellClass.spell', 'spell', 'WITH')
            ->leftJoin('spellClass.characterClass', 'characterClass', 'WITH')
            ->addSelect('characterClass', 'spell')
            ->andWhere('spell = :spell')
            ->setParameter('spell', $spell)
            ->andWhere('spellClass.characterClass = :class2')
            ->setParameter('class2', $class)
            ->getQuery()->getOneOrNullResult();
    }

    /**
     * @return array|SpellClass[]
     */
    public function searchByNameAndClass(?string $name = null, ?CharacterClass $class = null): array
    {
        $qb = $this->createQueryBuilder('spellClass')
            ->leftJoin('spellClass.spell', 'spell', 'WITH')
            ->leftJoin('spellClass.characterClass', 'characterClass', 'WITH')
            ->addSelect('characterClass', 'spell');

        if ($name) {
            $qb->andWhere('spell.name LIKE :name')
                ->setParameter('name', '%'.$name.'%');
        }

        if ($class instanceof CharacterClass) {
            $qb->andWhere('spellClass.characterClass = :class2')
                ->setParameter('class2', $class);
        }

        $qb->addOrderBy('spell.name');

        return $qb->getQuery()->getResult();
    }
}
