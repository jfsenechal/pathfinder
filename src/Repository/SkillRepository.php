<?php

namespace AfmLibre\Pathfinder\Repository;

use AfmLibre\Pathfinder\Doctrine\OrmCrudTrait;
use AfmLibre\Pathfinder\Entity\Skill;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Skill|null find($id, $lockMode = null, $lockVersion = null)
 * @method Skill|null findOneBy(array $criteria, array $orderBy = null)
 * @method Skill[]    findAll()
 * @method Skill[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SkillRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Skill::class);
    }

    /**
     * @return Skill[]
     */
    public function findAllOrdered(): array
    {
        return $this->createQbl()
            ->addOrderBy('skill.name', Criteria::ASC)
            ->getQuery()
            ->getResult();
    }

    public function findOneByName(string $name): ?Skill
    {
        return $this->createQbl()
            ->andWhere('skill.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }

    private function createQbl(): QueryBuilder
    {
        return $this->createQueryBuilder('skill')
            ->orderBy('skill.name', 'ASC');
    }

}
