<?php


namespace AfmLibre\Pathfinder\Repository;


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
