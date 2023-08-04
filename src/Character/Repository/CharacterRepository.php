<?php

namespace AfmLibre\Pathfinder\Character\Repository;

use AfmLibre\Pathfinder\Doctrine\OrmCrudTrait;
use AfmLibre\Pathfinder\Entity\Character;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

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
     * @return Character[]
     */
    public function searchByUser(?string $username = null): array
    {
        $qb = $this->createQbl();

        if ($username) {
            $qb->andWhere('character.name LIKE :name')
                ->setParameter('name', '%'.$username.'%');
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @param UserInterface|null $user
     * @return Character[]
     */
    public function findByUser(?UserInterface $user): array
    {
        return $this->createQbl()->getQuery()->getResult();
    }

    private function createQbl(): QueryBuilder
    {
        return $this->createQueryBuilder('character')
            ->leftJoin('character.classT', 'classT', 'WITH')
            ->leftJoin('character.race', 'race', 'WITH')
            ->addSelect('classT', 'race')
            ->orderBy('character.name', 'ASC');
    }

}
