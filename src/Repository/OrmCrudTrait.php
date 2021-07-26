<?php


namespace AfmLibre\Pathfinder\Repository;


use AfmLibre\Pathfinder\Entity\SpellProfile;
use Doctrine\ORM\EntityManager;

trait OrmCrudTrait
{
    /**
     * @var EntityManager
     */
    protected $_em;

    public function persist(object $character)
    {
        $this->_em->persist($character);
    }

    public function flush()
    {
        $this->_em->flush();
    }

    public function remove(object $object)
    {
        $this->_em->remove($object);
    }

    public function getOriginalEntityData(SpellProfile $spellProfile)
    {
        return $this->_em->getUnitOfWork()->getOriginalEntityData($spellProfile);
    }
}
