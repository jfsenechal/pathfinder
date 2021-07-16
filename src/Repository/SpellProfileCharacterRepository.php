<?php

namespace AfmLibre\Pathfinder\Repository;

use AfmLibre\Pathfinder\Entity\SpellProfile;
use AfmLibre\Pathfinder\Entity\SpellProfileCharacterSpell;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SpellProfileCharacterSpell|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpellProfileCharacterSpell|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpellProfileCharacterSpell[]    findAll()
 * @method SpellProfileCharacterSpell[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpellProfileCharacterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, SpellProfileCharacterSpell::class);
    }

    /**
     * @return array|SpellProfileCharacterSpell[]
     */
    public function searchByCharacter(SpellProfile $character): array
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

    public function persist(SpellProfileCharacterSpell $spellClass)
    {
        $this->_em->persist($spellClass);
    }

    public function flush()
    {
        $this->_em->flush();
    }
}
