<?php


namespace AfmLibre\Pathfinder\Spell\MessageHandler;


use AfmLibre\Pathfinder\Spell\Message\SpellAvailableUpdated;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SpellSelectionUpdatedHandler implements MessageHandlerInterface
{
    private FlashBagInterface $flashBag;

    public function __construct(FlashBagInterface $flashBag)
    {
        $this->flashBag = $flashBag;
    }

    public function __invoke(SpellAvailableUpdated $spellSelectionUpdated): void
    {
        $this->flashBag->add('success', "La séléction a bien été modifiée");
    }
}
