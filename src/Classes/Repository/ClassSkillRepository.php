<?php

namespace AfmLibre\Pathfinder\Classes\Repository;

use AfmLibre\Pathfinder\Doctrine\OrmCrudTrait;
use AfmLibre\Pathfinder\Entity\ClassT;
use AfmLibre\Pathfinder\Entity\Skill;
use AfmLibre\Pathfinder\Entity\ClassSkill;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ClassSkill|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClassSkill|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClassSkill[]    findAll()
 * @method ClassSkill[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClassSkillRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, ClassSkill::class);
    }

    /**
     * @return ClassSkill[]
     */
    public function findByClass(ClassT $class): array
    {
        return $this->createQbl()
            ->andWhere('class_skill.classT = :class')
            ->setParameter('class', $class)
            ->addOrderBy('skill.name')
            ->getQuery()->getResult();
    }

    /**
     * @return ClassSkill[]
     */
    public function findBySkill(Skill $skill): array
    {
        return $this->createQbl()
            ->andWhere('class_skill.skill = :skill')
            ->setParameter('skill', $skill)
            ->addOrderBy('classT.name')
            ->getQuery()->getResult();
    }

    private function createQbl(): QueryBuilder
    {
        return $this->createQueryBuilder('class_skill')
            ->leftJoin('class_skill.skill', 'skill', 'WITH')
            ->leftJoin('class_skill.classT', 'classT', 'WITH')
            ->addSelect('skill', 'classT');
    }

}
