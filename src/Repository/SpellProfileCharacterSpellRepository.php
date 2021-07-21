<?php


namespace AfmLibre\Pathfinder\Repository;


use AfmLibre\Pathfinder\Entity\SpellProfile;
use AfmLibre\Pathfinder\Entity\SpellProfileCharacterSpell;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SpellProfileCharacterSpell|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpellProfileCharacterSpell|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpellProfileCharacterSpell[]    findAll()
 * @method SpellProfileCharacterSpell[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpellProfileCharacterSpellRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, SpellProfileCharacterSpell::class);
    }

    /**
     * @return SpellProfileCharacterSpell[]
     */
    public function findBySpellProfile(SpellProfile $spellProfile): array
    {
        return $this->createQueryBuilder('spcs')
            ->leftJoin('spcs.spell_profile', 'spellProfile')
            ->addSelect('spellProfile')
            ->andWhere('spcs.spell_profile = :profile')
            ->setParameter('profile', $spellProfile)
            ->addOrderBy('spcs.id')
            ->getQuery()
            ->getResult();
    }

    public function persist(SpellProfileCharacterSpell $spellProfileCharacterSpell)
    {
        $this->_em->persist($spellProfileCharacterSpell);
    }

    public function remove(SpellProfileCharacterSpell $spellProfileCharacterSpell)
    {
        $this->_em->remove($spellProfileCharacterSpell);
    }

    public function flush()
    {
        $this->_em->flush();
    }

}
