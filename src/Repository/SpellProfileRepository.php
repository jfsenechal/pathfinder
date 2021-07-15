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
     * @return array|SpellProfile[]
     */
    public function searchByCharacter(Character $character): array
    {
        return $this->createQueryBuilder('spellProfile')
            ->leftJoin('spellProfile.character_player', 'character', 'WITH')
            ->leftJoin('spellProfile.character_spells', 'character_spells', 'WITH')
            ->addSelect('character', 'character_spells')
            ->andWhere('spellProfile.character_player = :character')
            ->setParameter('character', $character)
            ->addOrderBy('spellProfile.name')
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
