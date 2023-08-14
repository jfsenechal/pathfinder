<?php

namespace AfmLibre\Pathfinder\Spell\Repository;

use AfmLibre\Pathfinder\Doctrine\OrmCrudTrait;
use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\FavoriteSpell;
use AfmLibre\Pathfinder\Entity\Spell;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FavoriteSpell|null find($id, $lockMode = null, $lockVersion = null)
 * @method FavoriteSpell|null findOneBy(array $criteria, array $orderBy = null)
 * @method FavoriteSpell[]    findAll()
 * @method FavoriteSpell[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FavoriteSpellRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FavoriteSpell::class);
    }

    /**
     * @return FavoriteSpell[]
     */
    public function findByCharacter(Character $character): array
    {
        return $this->createQbl()
            ->andWhere('favorite_spell.character = :character')
            ->setParameter('character', $character)
            ->addOrderBy('favorite_spell.level', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByCharacterAndSpell(Character $character, Spell $spell): ?FavoriteSpell
    {
        return $this->createQbl()
            ->andWhere('favorite_spell.character = :character')
            ->setParameter('character', $character)
            ->andWhere('spell = :spell')
            ->setParameter('spell', $spell)
            ->getQuery()
            ->getOneOrNullResult();
    }

    private function createQbl(): QueryBuilder
    {
        return $this->createQueryBuilder('favorite_spell')
            ->leftJoin('favorite_spell.spell', 'spell', 'WITH')
            ->leftJoin('favorite_spell.character', 'character', 'WITH')
            ->addSelect('spell', 'character')
            ->orderBy('spell.name', 'ASC');
    }
}
