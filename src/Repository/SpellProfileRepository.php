<?php

namespace AfmLibre\Pathfinder\Repository;

use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\CharacterClass;
use AfmLibre\Pathfinder\Entity\Spell;
use AfmLibre\Pathfinder\Entity\SpellProfile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SpellProfile|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpellProfile|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpellProfile[]    findAll()
 * @method SpellProfile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpellProfileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, SpellProfile::class);
    }

    /**
     * @param string|null $name
     * @param \AfmLibre\Pathfinder\Entity\CharacterClass|null $class
     * @return array|SpellProfile[]
     */
    public function searchByCharacter(Character $character): array
    {
        return $this->createQueryBuilder('spellProfile')
            ->leftJoin('spellProfile.spell', 'spell', 'WITH')
            ->leftJoin('spellClass.characterClass', 'characterClass', 'WITH')
            ->addSelect('characterClass', 'spell')
            ->andWhere('spellClass.characterClass = :class2')
            ->setParameter('class2', $character)
            ->addOrderBy('spell.name')
            ->getQuery()->getResult();
    }

    public function persist(SpellProfile $spellClass)
    {
        $this->_em->persist($spellClass);
    }

    public function flush()
    {
        $this->_em->flush();
    }
}
