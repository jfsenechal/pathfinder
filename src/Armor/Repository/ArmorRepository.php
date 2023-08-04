<?php

namespace AfmLibre\Pathfinder\Armor\Repository;

use AfmLibre\Pathfinder\Doctrine\OrmCrudTrait;
use AfmLibre\Pathfinder\Entity\Armor;
use AfmLibre\Pathfinder\Entity\ArmorCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Armor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Armor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Armor[]    findAll()
 * @method Armor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArmorRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Armor::class);
    }

    /**
     * @return array|Armor[]
     */
    public function findAllOrdered(): array
    {
        return $this->createQbl()
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array|Armor[]
     */
    public function findByCategory(ArmorCategory $category): array
    {
        return $this->createQbl()
            ->andWhere('armor.category = :category')
            ->setParameter('category', $category)
            ->getQuery()
            ->getResult();
    }

    public function findOneByName(string $name): ?Armor
    {
        return $this->createQbl()
            ->andWhere('armor.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }

    private function createQbl(): QueryBuilder
    {
        return $this->createQueryBuilder('armor')
            ->leftJoin('armor.category', 'category', 'WITH')
            ->addSelect('category')
            ->orderBy('armor.name', 'ASC');
    }
}
