<?php

namespace AfmLibre\Pathfinder\Repository;

use AfmLibre\Pathfinder\Doctrine\OrmCrudTrait;
use AfmLibre\Pathfinder\Entity\ClassT;
use AfmLibre\Pathfinder\Entity\Skill;
use AfmLibre\Pathfinder\Entity\SkillClass;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SkillClass|null find($id, $lockMode = null, $lockVersion = null)
 * @method SkillClass|null findOneBy(array $criteria, array $orderBy = null)
 * @method SkillClass[]    findAll()
 * @method SkillClass[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SkillClassRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, SkillClass::class);
    }

    /**
     * @return SkillClass[]
     */
    public function findByClass(ClassT $class): array
    {
        return $this->createQbl()
            ->andWhere('skill_class.classT = :class')
            ->setParameter('class', $class)
            ->addOrderBy('skill.name')
            ->getQuery()->getResult();
    }

    /**
     * @return SkillClass[]
     */
    public function findBySkill(Skill $skill): array
    {
        return $this->createQbl()
            ->andWhere('skill_class.skill = :skill')
            ->setParameter('skill', $skill)
            ->addOrderBy('classT.name')
            ->getQuery()->getResult();
    }

    private function createQbl(): QueryBuilder
    {
        return $this->createQueryBuilder('skill_class')
            ->leftJoin('skill_class.skill', 'skill', 'WITH')
            ->leftJoin('skill_class.classT', 'classT', 'WITH')
            ->addSelect('skill', 'classT');
    }

}
