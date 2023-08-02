<?php

namespace AfmLibre\Pathfinder\Repository;

use AfmLibre\Pathfinder\Doctrine\OrmCrudTrait;
use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\SpellProfile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SpellProfile|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpellProfile|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpellProfile[]    findAll()
 * @method SpellProfile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpellProfileRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, SpellProfile::class);
    }

    /**
     * @return array|SpellProfile[]
     */
    public function findByCharacter(Character $character): array
    {
        return $this->createQbl()
            ->andWhere('character = :character')
            ->setParameter('character', $character)
            ->addOrderBy('spellProfile.name')
            ->getQuery()->getResult();
    }

    private function createQbl(): QueryBuilder
    {
        return $this->createQueryBuilder('spell_profile')
            ->leftJoin('spell_profile.character', 'character', 'WITH')
            ->addSelect('character')
            ->orderBy('spell_profile.name', 'ASC');
    }

}
