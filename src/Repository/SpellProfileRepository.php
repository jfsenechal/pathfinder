<?php

namespace AfmLibre\Pathfinder\Repository;

use AfmLibre\Pathfinder\Doctrine\OrmCrudTrait;
use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\SpellProfile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
        return $this->createQueryBuilder('spellProfile')
            ->leftJoin('spellProfile.character', 'character', 'WITH')
            ->leftJoin('spellProfile.spells_profile_character', 'spells_profile_character', 'WITH')
            ->addSelect('character', 'spells_profile_character')
            ->andWhere('spellProfile.character = :character')
            ->setParameter('character', $character)
            ->addOrderBy('spellProfile.name')
            ->getQuery()->getResult();
    }
}
