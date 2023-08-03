<?php


namespace AfmLibre\Pathfinder\Spell\MessageHandler;

use AfmLibre\Pathfinder\Spell\Message\FavoriteSpellUpdated;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
class FavoriteSpellUpdatedHandler
{
    private FlashBagInterface $flashBag;

    public function __construct(RequestStack $requestStack)
    {
        $this->flashBag = $requestStack->getSession()->getFlashBag();
    }

    public function __invoke(FavoriteSpellUpdated $favoriteSpellUpdated): void
    {
        $this->flashBag->add('success', "La séléction a bien été modifiée");
    }
}
