<?php


namespace AfmLibre\Pathfinder\Spell\Factory;

use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Form\SelectionType;
use AfmLibre\Pathfinder\Repository\FavoriteSpellRepository;
use AfmLibre\Pathfinder\Spell\Dto\FavoriteSpellDto;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class FormFactory
{
    public function __construct(
        private readonly FavoriteSpellRepository $characterSpellRepository,
        private readonly FormFactoryInterface $formFactory
    ) {
    }

    public function createFormFavoriteSpells(Character $character, array $favoriteSpells): FormInterface
    {
        $characterSpells = $this->characterSpellRepository->findByCharacter($character);
        $characterSpells2 = array_map(
            fn($characterSpell) => $characterSpell->spell,
            $characterSpells
        );

        $favoriteSpellDto = new FavoriteSpellDto($character, $characterSpells2);

        return $this->formFactory->create(
            SelectionType::class,
            $favoriteSpellDto,
            [
                'spells' => $favoriteSpells,
            ]
        );
    }
}
