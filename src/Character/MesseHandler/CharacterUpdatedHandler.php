<?php


namespace AfmLibre\Pathfinder\Character\MesseHandler;


use AfmLibre\Pathfinder\Character\Message\CharacterUpdated;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CharacterUpdatedHandler implements MessageHandlerInterface
{
    private FlashBagInterface $flashBag;

    public function __construct(FlashBagInterface $flashBag)
    {
        $this->flashBag = $flashBag;
    }

    public function __invoke(CharacterUpdated $characterUpdated): void
    {
        $this->flashBag->add('success', "Le personnage a bien été modifié");
    }
}
