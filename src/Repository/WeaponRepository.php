<?php

namespace AfmLibre\Pathfinder\Repository;

use AfmLibre\Pathfinder\Doctrine\OrmCrudTrait;
use AfmLibre\Pathfinder\Entity\Weapon;
use AfmLibre\Pathfinder\Entity\WeaponCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Weapon|null find($id, $lockMode = null, $lockVersion = null)
 * @method Weapon|null findOneBy(array $criteria, array $orderBy = null)
 * @method Weapon[]    findAll()
 * @method Weapon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WeaponRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Weapon::class);
    }

    /**
     * @return Weapon[]
     */
    public function findAllOrdered(): array
    {
        return $this->createQueryBuilder('weapon')
            ->orderBy('weapon.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Weapon[]
     */
    public function findByCategory(WeaponCategory $category): array
    {
        return $this->createQueryBuilder('weapon')
            ->andWhere('weapon.category = :category')
            ->setParameter('category', $category)
            ->orderBy('weapon.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByName(string $name): ?Weapon
    {
        return $this->createQueryBuilder('weapon')
            ->andWhere('weapon.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
