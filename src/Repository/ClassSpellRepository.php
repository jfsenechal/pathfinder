<?php

namespace AfmLibre\Pathfinder\Repository;

use AfmLibre\Pathfinder\Doctrine\OrmCrudTrait;
use AfmLibre\Pathfinder\Entity\ClassT;
use AfmLibre\Pathfinder\Entity\Spell;
use AfmLibre\Pathfinder\Entity\ClassSpell;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
        return $this->createQueryBuilder('classSpell')
            ->leftJoin('classSpell.spell', 'spell', 'WITH')
            ->leftJoin('classSpell.classT', 'classT', 'WITH')
            ->addSelect('classT', 'spell')
            ->andWhere('spell = :spell')
            ->setParameter('spell', $spell)
            ->andWhere('classSpell.classT = :class2')
            ->setParameter('class2', $class)
            ->getQuery()->getOneOrNullResult();
    }

    /**
     * @return array|ClassSpell[]
     */
    public function searchByNameAndClass(?string $name = null, ?ClassT $class = null): array
    {
        $qb = $this->createQueryBuilder('classSpell')
            ->leftJoin('classSpell.spell', 'spell', 'WITH')
            ->leftJoin('classSpell.classT', 'classT', 'WITH')
            ->addSelect('classT', 'spell');

        if ($name) {
            $qb->andWhere('spell.name LIKE :name')
                ->setParameter('name', '%' . $name . '%');
        }

        if ($class instanceof ClassT) {
            $qb->andWhere('classSpell.classT = :class2')
                ->setParameter('class2', $class);
        }

        $qb->addOrderBy('spell.name');

        return $qb->getQuery()->getResult();
    }
}
