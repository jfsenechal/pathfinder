<?php

namespace AfmLibre\Pathfinder\Repository;

use AfmLibre\Pathfinder\Doctrine\OrmCrudTrait;
use AfmLibre\Pathfinder\Entity\Feat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
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
    public function findAllOrdered(): array
    {
        return $this->createQueryBuilder('feat')
            ->addOrderBy('feat.name', Criteria::ASC)
            ->getQuery()
            ->getResult();
    }

}
