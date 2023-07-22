<?php

namespace AfmLibre\Pathfinder\Repository;

use AfmLibre\Pathfinder\Doctrine\OrmCrudTrait;
use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\CharacterSpell;
use AfmLibre\Pathfinder\Entity\Spell;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CharacterSpell|null find($id, $lockMode = null, $lockVersion = null)
 * @method CharacterSpell|null findOneBy(array $criteria, array $orderBy = null)
 * @method CharacterSpell[]    findAll()
 * @method CharacterSpell[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CharacterSpellRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CharacterSpell::class);
    }

    /**
     * @return CharacterSpell[] Returns an array of CharacterSpell objects
     */
    public function findByCharacter(Character $character): array
    {
        return $this->createQueryBuilder('character_spell')
            ->leftJoin('character_spell.spell', 'spell', 'WITH')
            ->leftJoin('character_spell.character_player', 'character_player', 'WITH')
            ->addSelect('spell', 'character_player')
            ->andWhere('character_spell.character_player = :character')
            ->setParameter('character', $character)
            ->addOrderBy('character_spell.level', 'ASC')
            ->addOrderBy('spell.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByCharacterAndSpell(Character $character, Spell $spell): ?CharacterSpell
    {
        return $this->createQueryBuilder('character_spell')
            ->leftJoin('character_spell.spell', 'spell', 'WITH')
            ->leftJoin('character_spell.character_player', 'character_player', 'WITH')
            ->addSelect('spell', 'character_player')
            ->andWhere('character_spell.character_player = :character')
            ->setParameter('character', $character)
            ->andWhere('spell = :spell')
            ->setParameter('spell', $spell)
            ->orderBy('spell.name', 'ASC')
            ->getQuery()
            ->getOneOrNullResult();
    }
}
