<?php

namespace AfmLibre\Pathfinder\Classes\Repository;

use AfmLibre\Pathfinder\Doctrine\OrmCrudTrait;
use AfmLibre\Pathfinder\Entity\ClassSpell;
use AfmLibre\Pathfinder\Entity\ClassT;
use AfmLibre\Pathfinder\Entity\Spell;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ClassSpell|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClassSpell|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClassSpell[]    findAll()
 * @method ClassSpell[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClassSpellRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, ClassSpell::class);
    }

    public function searchByClassAndSpell(?ClassT $class, Spell $spell): ?ClassSpell
    {
        return $this->createQbl()
            ->andWhere('spell = :spell')
            ->setParameter('spell', $spell)
            ->andWhere('class_spell.classT = :classT')
            ->setParameter('classT', $class)
            ->getQuery()->getOneOrNullResult();
    }

    /**
     * @return array|ClassSpell[]
     */
    public function searchByNameAndClass(?string $name = null, ?ClassT $class = null): array
    {
        $qb = $this->createQbl();

        if ($name) {
            $qb->andWhere('spell.name LIKE :name')
                ->setParameter('name', '%'.$name.'%');
        }

        if ($class instanceof ClassT) {
            $qb->andWhere('class_spell.classT = :class2')
                ->setParameter('class2', $class);
        }

        return $qb->getQuery()->getResult();
    }

    private function createQbl(): QueryBuilder
    {
        return $this->createQueryBuilder('class_spell')
            ->leftJoin('class_spell.spell', 'spell', 'WITH')
            ->leftJoin('class_spell.classT', 'classT', 'WITH')
            ->addSelect('classT', 'spell')
            ->addOrderBy('spell.name');
    }
}
