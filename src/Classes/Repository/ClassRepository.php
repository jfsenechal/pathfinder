<?php

namespace AfmLibre\Pathfinder\Classes\Repository;

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

    public function findOneByName(string $name): ?ClassT
    {
        return $this->createQbl()
            ->andWhere('class_t.name = :name')
            ->setParameter('name', $name)
            ->getQuery()->getOneOrNullResult();
    }

    public function getQl(): QueryBuilder
    {
        return $this->createQbl()
            ->orderBy('class_t.name');
    }

    /**
     * @return array|ClassT[]
     */
    public function searchByName(?string $name = null): array
    {
        $qb = $this->createQbl();
        if ($name) {
            $qb->andWhere('class_t.name LIKE :name')
                ->setParameter('name', '%' . $name . '%');
        }
        $qb->orderBy('class_t.name');

        return $qb->getQuery()->getResult();
    }

    private function createQbl(): QueryBuilder
    {
        return $this->createQueryBuilder('class_t')
            ->addOrderBy('class_t.name', 'ASC');
    }
}
