<?php


namespace AfmLibre\Pathfinder\Level\MesseHandler;

use AfmLibre\Pathfinder\Level\Message\LevelCrossingUpdated;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
class LevelCrossingUpdatedHandler
{
    private FlashBagInterface $flashBag;

    public function __construct(RequestStack $requestStack)
    {
        $this->flashBag = $requestStack->getSession()?->getFlashBag();
    }

    public function __invoke(LevelCrossingUpdated $characterUpdated): void
    {
        $this->flashBag->add('success', "Le changement de niveau a bien été fait");
    }
}
