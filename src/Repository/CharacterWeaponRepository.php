<?php

namespace AfmLibre\Pathfinder\Repository;

use AfmLibre\Pathfinder\Doctrine\OrmCrudTrait;
use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\CharacterWeapon;
use AfmLibre\Pathfinder\Entity\Weapon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
     * @return CharacterWeapon[] Returns an array of CharacterWeapon objects
     */
    public function findByCharacter(Character $character): array
    {
        return $this->createQueryBuilder('character_weapon')
            ->leftJoin('character_weapon.weapon', 'weapon', 'WITH')
            ->leftJoin('character_weapon.character', 'character', 'WITH')
            ->addSelect('weapon', 'character')
            ->andWhere('character_weapon.character = :character')
            ->setParameter('character', $character)
            ->addOrderBy('character_weapon.level', 'ASC')
            ->addOrderBy('weapon.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByCharacterAndWeapon(Character $character, Weapon $weapon): ?CharacterWeapon
    {
        return $this->createQueryBuilder('character_weapon')
            ->leftJoin('character_weapon.weapon', 'weapon', 'WITH')
            ->leftJoin('character_weapon.character', 'character', 'WITH')
            ->addSelect('weapon', 'character')
            ->andWhere('character_weapon.character = :character')
            ->setParameter('character', $character)
            ->andWhere('weapon = :weapon')
            ->setParameter('weapon', $weapon)
            ->orderBy('weapon.name', 'ASC')
            ->getQuery()
            ->getOneOrNullResult();
    }
}
