<?php

namespace AfmLibre\Pathfinder\Weapon\Repository;

use AfmLibre\Pathfinder\Doctrine\OrmCrudTrait;
use AfmLibre\Pathfinder\Entity\Weapon;
use AfmLibre\Pathfinder\Entity\WeaponCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
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
        return $this->createQbl()
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Weapon[]
     */
    public function findByCategory(WeaponCategory $category): array
    {
        return $this->createQbl()
            ->andWhere('weapon.category = :category')
            ->setParameter('category', $category)
            ->getQuery()
            ->getResult();
    }

    public function findOneByName(string $name): ?Weapon
    {
        return $this->createQbl()
            ->andWhere('weapon.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }

    private function createQbl(): QueryBuilder
    {
        return $this->createQueryBuilder('weapon')
            ->leftJoin('weapon.category', 'category', 'WITH')
            ->addSelect('category')
            ->orderBy('weapon.name', 'ASC');
    }

}
