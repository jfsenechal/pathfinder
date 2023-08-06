<?php

namespace AfmLibre\Pathfinder\Character\Repository;

use AfmLibre\Pathfinder\Doctrine\OrmCrudTrait;
use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\CharacterWeapon;
use AfmLibre\Pathfinder\Entity\Weapon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CharacterWeapon|null find($id, $lockMode = null, $lockVersion = null)
 * @method CharacterWeapon|null findOneBy(array $criteria, array $orderBy = null)
 * @method CharacterWeapon[]    findAll()
 * @method CharacterWeapon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CharacterWeaponRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CharacterWeapon::class);
    }

    /**
     * @return CharacterWeapon[]
     */
    public function findByCharacter(Character $character): array
    {
        return $this->createQbl()
            ->andWhere('character_weapon.character = :character')
            ->setParameter('character', $character)
            ->getQuery()
            ->getResult();
    }

    public function finOneByCharacterAndArmor(Character $character, Weapon $weapon): ?CharacterWeapon
    {
        return $this->createQbl()
            ->andWhere('character_weapon.character = :character')
            ->setParameter('character', $character)
            ->andWhere('character_weapon.weapon = :weapon')
            ->setParameter('weapon', $weapon)
            ->getQuery()
            ->getOneOrNullResult();
    }

    private function createQbl(): QueryBuilder
    {
        return $this->createQueryBuilder('character_weapon')
            ->leftJoin('character_weapon.weapon', 'weapon', 'WITH')
            ->leftJoin('character_weapon.character', 'character', 'WITH')
            ->addSelect('weapon', 'character')
            ->addOrderBy('weapon.name', 'ASC');
    }

}
