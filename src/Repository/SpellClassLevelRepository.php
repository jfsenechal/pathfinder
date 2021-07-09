<?php

namespace AfmLibre\Pathfinder\Repository;

use AfmLibre\Pathfinder\Entity\SpellClassLevel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SpellClassLevel|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpellClassLevel|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpellClassLevel[]    findAll()
 * @method SpellClassLevel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpellClassLevelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, SpellClassLevel::class);
    }

    public function persist(SpellClassLevel $spellClassLevel)
    {
        $this->_em->persist($spellClassLevel);
    }

    public function flush()
    {
        $this->_em->flush();
    }
}
