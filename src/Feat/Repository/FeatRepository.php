<?php

namespace AfmLibre\Pathfinder\Feat\Repository;

use AfmLibre\Pathfinder\Doctrine\OrmCrudTrait;
use AfmLibre\Pathfinder\Entity\Feat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Feat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Feat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Feat[]    findAll()
 * @method Feat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeatRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Feat::class);
    }

    /**
     * @return Feat[]
     */
    public function search(?string $name = null, ?string $category = null, ?string $source = null): array
    {
        $qb = $this->createQbl();

        if ($name) {
            $qb
                ->andWhere('feat.name LIKE :name')
                ->setParameter('name', '%' . $name . '%');
        }

        if ($category) {
            $qb
                ->andWhere('feat.category LIKE :cat')
                ->setParameter('cat', $category);
        }

        if ($source) {
            $qb
                ->andWhere('feat.sourced LIKE :src')
                ->setParameter('src', $source);
        }

        return $qb->addOrderBy('feat.name', Criteria::ASC)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Feat[]
     */
    public function findAllOrdered(): array
    {
        return $this->createQbl()
            ->andWhere('feat.sourced LIKE :src')
            ->setParameter('src', 'MJ')
            ->getQuery()
            ->getResult();
    }

    public function findOneByName(string $name): ?Feat
    {
        return $this->createQbl()
            ->andWhere('feat.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }

    private function createQbl(): QueryBuilder
    {
        return $this->createQueryBuilder('feat')
            ->addOrderBy('feat.name', Criteria::ASC);
    }
}
