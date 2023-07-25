<?php

namespace AfmLibre\Pathfinder\Repository;

use AfmLibre\Pathfinder\Doctrine\OrmCrudTrait;
use AfmLibre\Pathfinder\Entity\ClassT;
use AfmLibre\Pathfinder\Entity\Spell;
use AfmLibre\Pathfinder\Entity\SpellClass;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SpellClass|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpellClass|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpellClass[]    findAll()
 * @method SpellClass[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpellClassRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, SpellClass::class);
    }

    public function searchByClassAndSpell(?ClassT $class, Spell $spell): ?SpellClass
    {
        return $this->createQueryBuilder('spellClass')
            ->leftJoin('spellClass.spell', 'spell', 'WITH')
            ->leftJoin('spellClass.classT', 'classT', 'WITH')
            ->addSelect('classT', 'spell')
            ->andWhere('spell = :spell')
            ->setParameter('spell', $spell)
            ->andWhere('spellClass.classT = :class2')
            ->setParameter('class2', $class)
            ->getQuery()->getOneOrNullResult();
    }

    /**
     * @return array|SpellClass[]
     */
    public function searchByNameAndClass(?string $name = null, ?ClassT $class = null): array
    {
        $qb = $this->createQueryBuilder('spellClass')
            ->leftJoin('spellClass.spell', 'spell', 'WITH')
            ->leftJoin('spellClass.classT', 'classT', 'WITH')
            ->addSelect('classT', 'spell');

        if ($name) {
            $qb->andWhere('spell.name LIKE :name')
                ->setParameter('name', '%' . $name . '%');
        }

        if ($class instanceof ClassT) {
            $qb->andWhere('spellClass.classT = :class2')
                ->setParameter('class2', $class);
        }

        $qb->addOrderBy('spell.name');

        return $qb->getQuery()->getResult();
    }
}
