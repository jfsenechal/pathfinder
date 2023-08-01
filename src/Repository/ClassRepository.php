<?php

namespace AfmLibre\Pathfinder\Repository;

use AfmLibre\Pathfinder\Doctrine\OrmCrudTrait;
use AfmLibre\Pathfinder\Entity\ClassT;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ClassT|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClassT|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClassT[]    findAll()
 * @method ClassT[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClassRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, ClassT::class);
    }

    public function findByShortName(string $name): ?ClassT
    {
        return $this->createQueryBuilder('class_t')
            ->andWhere('class_t.short_name = :name')
            ->setParameter('name', $name)
            ->getQuery()->getOneOrNullResult();
    }

    public function findOneByName(string $name): ?ClassT
    {
        return $this->createQueryBuilder('class_t')
            ->andWhere('class_t.name = :name')
            ->setParameter('name', $name)
            ->getQuery()->getOneOrNullResult();
    }

    public function getQl(): QueryBuilder
    {
        return $this->createQueryBuilder('class_t')
            ->orderBy('class_t.name');
    }

    /**
     * @return array|ClassT[]
     */
    public function searchByName(?string $name = null): array
    {
        $qb = $this->createQueryBuilder('class_t');
        if ($name) {
            $qb->andWhere('class_t.name LIKE :name')
                ->setParameter('name', '%' . $name . '%');
        }
        $qb->orderBy('class_t.name');

        return $qb->getQuery()->getResult();
    }
}
