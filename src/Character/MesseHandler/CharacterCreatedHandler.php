<?php

namespace AfmLibre\Pathfinder\Character\MesseHandler;

use AfmLibre\Pathfinder\Character\Message\CharacterCreated;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
class CharacterCreatedHandler
{
    private FlashBagInterface $flashBag;

    public function __construct(RequestStack $requestStack)
    {
        $this->flashBag = $requestStack->getSession()?->getFlashBag();
    }

    public function __invoke(CharacterCreated $characterUpdated): void
    {
        $this->flashBag->add('success', "Le personnage a bien été créé");
    }
}
