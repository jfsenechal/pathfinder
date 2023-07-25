<?php

namespace AfmLibre\Pathfinder\Repository;

use AfmLibre\Pathfinder\Doctrine\OrmCrudTrait;
use AfmLibre\Pathfinder\Entity\Character;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Character|null find($id, $lockMode = null, $lockVersion = null)
 * @method Character|null findOneBy(array $criteria, array $orderBy = null)
 * @method Character[]    findAll()
 * @method Character[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CharacterRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Character::class);
    }

    /**
     * @return array|Character[]
     */
    public function searchByUser(?string $username = null): array
    {
        $qb = $this->createQueryBuilder('character')
            ->leftJoin('character.classT', 'classT', 'WITH')
            ->leftJoin('character.race', 'race', 'WITH')
            ->addSelect('classT', 'race');

        if ($username) {
            $qb->andWhere('character.name LIKE :name')
                ->setParameter('name', '%' . $username . '%');
        }

        $qb->addOrderBy('character.name');

        return $qb->getQuery()->getResult();
    }
}
