<?php

namespace AfmLibre\Pathfinder\Spell\Repository;

use AfmLibre\Pathfinder\Doctrine\OrmCrudTrait;
use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\ClassT;
use AfmLibre\Pathfinder\Entity\Spell;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Spell|null find($id, $lockMode = null, $lockVersion = null)
 * @method Spell|null findOneBy(array $criteria, array $orderBy = null)
 * @method Spell[]    findAll()
 * @method Spell[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpellRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Spell::class);
    }

    /**
     * @return array|Spell[]
     */
    public function searchByNameAndClassAndLevel(
        ?string $name = null,
        ?ClassT $class = null,
        ?int $level = null
    ): array {
        $qb = $this->createQbl();

        if ($name) {
            $qb->andWhere('spell.name LIKE :name')
                ->setParameter('name', '%'.$name.'%');
        }

        if ($class instanceof ClassT) {
            $qb->andWhere('class_spells.classT = :class2')
                ->setParameter('class2', $class);
        }

        if ($level !== null) {
            $qb->andWhere('class_spells.level = :level')
                ->setParameter('level', $level);
        }

        $qb->addOrderBy('spell.name');

        return $qb->getQuery()->getResult();
    }

    /**
     * @return array|Spell[]
     */
    public function findByCharacter(
        ?Character $character = null
    ): array {
        return $this->createQbl()
            ->andWhere('character.id = :character')
            ->setParameter('character', $character)
            ->addOrderBy('spell.name')
            ->getQuery()->getResult();
    }

    /**
     * @return Spell[]
     */
    public function findByClass(ClassT $class): array
    {
        return $this->createQbl()
            ->andWhere('class_spells.classT = :class')
            ->setParameter('class', $class)
            ->getQuery()->getResult();
    }

    private function createQbl(): QueryBuilder
    {
        return $this->createQueryBuilder('spell')
            ->leftJoin('spell.school', 'school', 'WITH')
            ->leftJoin('spell.class_spells', 'class_spells', 'WITH')
            ->addSelect('school','class_spells')
            ->addOrderBy('spell.name');
    }
}
