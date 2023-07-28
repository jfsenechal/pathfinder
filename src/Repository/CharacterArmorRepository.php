<?php

namespace AfmLibre\Pathfinder\Repository;

use AfmLibre\Pathfinder\Doctrine\OrmCrudTrait;
use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\CharacterArmor;
use AfmLibre\Pathfinder\Entity\Armor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CharacterArmor|null find($id, $lockMode = null, $lockVersion = null)
 * @method CharacterArmor|null findOneBy(array $criteria, array $orderBy = null)
 * @method CharacterArmor[]    findAll()
 * @method CharacterArmor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CharacterArmorRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CharacterArmor::class);
    }

    /**
     * @return CharacterArmor[]
     */
    public function findByCharacter(Character $character): array
    {
        return $this->createQueryBuilder('character_armor')
            ->leftJoin('character_armor.armor', 'armor', 'WITH')
            ->leftJoin('character_armor.character', 'character', 'WITH')
            ->addSelect('armor', 'character')
            ->andWhere('character_armor.character = :character')
            ->setParameter('character', $character)
            ->addOrderBy('armor.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

}
