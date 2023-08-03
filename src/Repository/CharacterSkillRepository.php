<?php

namespace AfmLibre\Pathfinder\Repository;

use AfmLibre\Pathfinder\Doctrine\OrmCrudTrait;
use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\CharacterSkill;
use AfmLibre\Pathfinder\Entity\Skill;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CharacterSkill|null find($id, $lockMode = null, $lockVersion = null)
 * @method CharacterSkill|null findOneBy(array $criteria, array $orderBy = null)
 * @method CharacterSkill[]    findAll()
 * @method CharacterSkill[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CharacterSkillRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CharacterSkill::class);
    }

    /**
     * @return CharacterSkill[]
     */
    public function findByCharacter(Character $character): array
    {
        return $this->createQbl()
            ->andWhere('character_skill.character = :character')
            ->setParameter('character', $character)
            ->getQuery()
            ->getResult();
    }

    public function findByCharacterAndSkill(?Character $character, ?Skill $skill): ?CharacterSkill
    {
        return $this->createQbl()
            ->andWhere('character_skill.character = :character')
            ->setParameter('character', $character)
            ->andWhere('character_skill.skill = :skill')
            ->setParameter('skill', $skill)
            ->getQuery()
            ->getOneOrNullResult();
    }

    private function createQbl(): QueryBuilder
    {
        return $this->createQueryBuilder('character_skill')
            ->leftJoin('character_skill.skill', 'skill', 'WITH')
            ->leftJoin('character_skill.character', 'character', 'WITH')
            ->addSelect('skill', 'character');
    }


}
