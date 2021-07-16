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
    private CharacterSpellRepository $characterSpellRepository;
    private FormFactoryInterface $formFactory;

    public function __construct(CharacterSpellRepository $characterSpellRepository, FormFactoryInterface $formFactory)
    {
        $this->characterSpellRepository = $characterSpellRepository;
        $this->formFactory = $formFactory;
    }

    public function createFormSelectionSpells(Character $character, array $spellsForSelection): FormInterface
    {
        $characterSpells = $this->characterSpellRepository->findByCharacter($character);
        $characterSpells2 = array_map(
            function ($characterSpell) {
                return $characterSpell->getSpell();
            },
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

    public function createFormProfileSpells(SpellProfile $spellProfile, array $characterSpells): FormInterface
    {
        /*   $characterSpells = $this->characterSpellRepository->findByCharacter($character);
           $characterSpells2 = array_map(
               function ($characterSpell) {
                   return $characterSpell->getSpell();
               },
               $characterSpells
           );

           $selection = new SpellSelectionDto($character, $characterSpells2);*/


        return $this->formFactory->create(
            SpellProfileSelectionType::class,
            $spellProfile,
            [
                'spells' => $characterSpells,
            ]
        );
    }
}
