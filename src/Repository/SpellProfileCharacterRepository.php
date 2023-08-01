<?php

namespace AfmLibre\Pathfinder\Repository;

use AfmLibre\Pathfinder\Doctrine\OrmCrudTrait;
use AfmLibre\Pathfinder\Entity\SpellProfile;
use AfmLibre\Pathfinder\Entity\SpellProfileCharacter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SpellProfileCharacter|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpellProfileCharacter|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpellProfileCharacter[]    findAll()
 * @method SpellProfileCharacter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpellProfileCharacterRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, SpellProfileCharacter::class);
    }

    /**
     * @return array|SpellProfileCharacter[]
     */
    public function searchByCharacter(SpellProfile $character): array
    {
        return $this->createQbl()
            ->andWhere('spellProfile.character = :character')
            ->setParameter('character', $character)
            ->addOrderBy('spellProfile.name')
            ->getQuery()->getResult();
    }

    public function findByProfileAndCharacterSpell(
        SpellProfile $spellProfile,
        $characterSpell
    ): ?SpellProfileCharacter {
        return $this->createQbl()
            ->andWhere('character_spell = :character')
            ->setParameter('character', $characterSpell)
            ->andWhere('spell_profile = :profile')
            ->setParameter('profile', $spellProfile)
            ->getQuery()->getOneOrNullResult();
    }

    /**
     * @return SpellProfileCharacter[]
     */
    public function findBySpellProfile(SpellProfile $spellProfile): array
    {
        return $this->createQbl()
            ->andWhere('spcs.spell_profile = :profile')
            ->setParameter('profile', $spellProfile)
            ->addOrderBy('spcs.id')
            ->getQuery()
            ->getResult();
    }

    private function createQbl(): QueryBuilder
    {
        return $this->createQueryBuilder('spell_profile_character')
            ->leftJoin('spell_profile_character.character_spell', 'character_spell', 'WITH')
            ->leftJoin('spell_profile_character.spell_profile', 'spell_profile', 'WITH')
            ->addSelect('character_spell', 'spell_profile');
    }

}
