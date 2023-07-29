<?php

namespace AfmLibre\Pathfinder\Repository;

use AfmLibre\Pathfinder\Doctrine\OrmCrudTrait;
use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\CharacterFeat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CharacterFeat|null find($id, $lockMode = null, $lockVersion = null)
 * @method CharacterFeat|null findOneBy(array $criteria, array $orderBy = null)
 * @method CharacterFeat[]    findAll()
 * @method CharacterFeat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CharacterFeatRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CharacterFeat::class);
    }

    /**
     * @return CharacterFeat[]
     */
    public function findByCharacter(Character $character): array
    {
        return $this->createQueryBuilder('character_feat')
            ->leftJoin('character_feat.feat', 'feat', 'WITH')
            ->leftJoin('character_feat.character', 'character', 'WITH')
            ->addSelect('feat', 'character')
            ->andWhere('character_feat.character = :character')
            ->setParameter('character', $character)
            ->addOrderBy('feat.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

}
