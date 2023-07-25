<?php


namespace AfmLibre\Pathfinder\Spell\Factory;

use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\SpellProfile;
use AfmLibre\Pathfinder\Form\SelectionType;
use AfmLibre\Pathfinder\Form\SpellProfileSelectionType;
use AfmLibre\Pathfinder\Repository\CharacterSpellRepository;
use AfmLibre\Pathfinder\Spell\Dto\SpellSelectionDto;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class FormFactory
{
    public function __construct(private readonly CharacterSpellRepository $characterSpellRepository, private readonly FormFactoryInterface $formFactory)
    {
    }

    public function createFormSelectionSpells(Character $character, array $spellsForSelection): FormInterface
    {
        $characterSpells = $this->characterSpellRepository->findByCharacter($character);
        $characterSpells2 = array_map(
            fn ($characterSpell) => $characterSpell->spell,
            $characterSpells
        );

        $selection = new SpellSelectionDto($character, $characterSpells2);

        return $this->formFactory->create(
            SelectionType::class,
            $selection,
            [
                'spells' => $spellsForSelection,
            ]
        );
    }
}
