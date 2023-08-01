<?php

namespace AfmLibre\Pathfinder\Repository;

use AfmLibre\Pathfinder\Doctrine\OrmCrudTrait;
use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\CharacterSpell;
use AfmLibre\Pathfinder\Entity\Spell;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
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
     * @return CharacterSpell[]
     */
    public function findByCharacter(Character $character): array
    {
        return $this->createQbl()
            ->andWhere('character_spell.character = :character')
            ->setParameter('character', $character)
            ->addOrderBy('character_spell.level', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByCharacterAndSpell(Character $character, Spell $spell): ?CharacterSpell
    {
        return $this->createQbl()
            ->andWhere('character_spell.character = :character')
            ->setParameter('character', $character)
            ->andWhere('spell = :spell')
            ->setParameter('spell', $spell)
            ->getQuery()
            ->getOneOrNullResult();
    }

    private function createQbl(): QueryBuilder
    {
        return $this->createQueryBuilder('character_spell')
            ->leftJoin('character_spell.spell', 'spell', 'WITH')
            ->leftJoin('character_spell.character', 'character', 'WITH')
            ->addSelect('spell', 'character')
            ->orderBy('character.name', 'ASC')
            ->orderBy('spell.name', 'ASC');
    }

}
