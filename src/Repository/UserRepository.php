<?php

namespace AfmLibre\Pathfinder\Repository;

use AfmLibre\Pathfinder\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function persist(User $user)
    {
        $this->_em->persist($user);
    }

    public function remove(User $user)
    {
        $this->_em->remove($user);
    }

    public function flush()
    {
        $this->_em->flush();
    }
}
