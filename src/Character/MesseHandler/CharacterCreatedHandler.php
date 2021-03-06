<?php


namespace AfmLibre\Pathfinder\Character\MesseHandler;


use AfmLibre\Pathfinder\Character\Message\CharacterCreated;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CharacterCreatedHandler implements MessageHandlerInterface
{
    private FlashBagInterface $flashBag;

    public function __construct(FlashBagInterface $flashBag)
    {
        $this->flashBag = $flashBag;
    }

    public function __invoke(CharacterCreated $characterUpdated): void
    {
        $this->flashBag->add('success', "Le personnage a bien été créé");
    }
}
