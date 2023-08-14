<?php


namespace AfmLibre\Pathfinder\Character\MesseHandler;

use AfmLibre\Pathfinder\Character\Message\CharacterUpdated;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
class CharacterUpdatedHandler
{
    private readonly FlashBagInterface $flashBag;

    public function __construct(RequestStack $requestStack)
    {
        $this->flashBag = $requestStack->getSession()->getFlashBag();
    }

    public function __invoke(CharacterUpdated $characterUpdated): void
    {
        $this->flashBag->add('success', "Le personnage a bien été modifié");
    }
}
