<?php

namespace AfmLibre\Pathfinder\SavingThrow;

use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\Feat;
use AfmLibre\Pathfinder\Entity\Modifier;
use AfmLibre\Pathfinder\Entity\Race;
use AfmLibre\Pathfinder\Modifier\ModifierEnum;
use AfmLibre\Pathfinder\Repository\CharacterFeatRepository;
use AfmLibre\Pathfinder\Repository\ModifierRepository;

class SavingThrowCalculator
{
    public function __construct(
        private ModifierRepository $modifierRepository,
        private CharacterFeatRepository $characterFeatRepository
    ) {
    }

    /**
     * @param Character $character
     * @return SavingThrowDto[]
     */
    public function calculate(Character $character,): array
    {
        $race = $character->race;

        $specials = [];
        if ($modifier = $this->modifierRepository->findOneByIdClassNameAndAbility(
            $race->getId(),
            $race::class,
            ModifierEnum::ABILITY_DEXTERITY
        )) {
            $specials = [$modifier];
        }

        $savingThrows = [];
        $currentLevel = $character->current_level;
        $savingThrows[] = new SavingThrowDto(
            'Réflexe',
            $currentLevel->reflex,
            'Dextérité',
            Character::getValueModifier($character->dexterity),
            $specials
        );

        $specials = [];
        if ($modifier = $this->modifierRepository->findOneByIdClassNameAndAbility(
            $race->getId(),
            $race::class,
            ModifierEnum::ABILITY_CONSTITUTION
        )) {
            $specials = [$modifier];
        }

        $savingThrows[] = new SavingThrowDto(
            'Vigueur',
            $currentLevel->fortitude,
            'Constitution',
            Character::getValueModifier($character->constitution),
            $specials
        );
        $specials = [];
        $savingThrows[] = new SavingThrowDto(
            'Volonté',
            $currentLevel->will,
            'Sagesse',
            Character::getValueModifier($character->wisdom),
            $specials
        );

        return $savingThrows;
    }

    /**
     * @param Character $character
     * @param ModifierEnum $modifierEnum
     * @return Modifier[]
     */
    private function findSpecials(Character $character, ModifierEnum $modifierEnum): array
    {
        $baseSpecials = [];
        /**
         * RACE
         */
        $race = $character->race;

        if ($modifier = $this->modifierRepository->findOneByIdClassNameAndAbility(
            $race->getId(),
            Race::class,
            $modifierEnum
        )) {
            $baseSpecials[] = $modifier;
        }

        /**
         * FEAT
         */
        foreach ($this->characterFeatRepository->findByCharacter($character) as $feat) {
            if ($modifier = $this->modifierRepository->findOneByIdClassNameAndAbility(
                $feat->getId(),
                Feat::class,
                $modifierEnum
            )) {
                $baseSpecials[] = $modifier;
            }
        }

        return $baseSpecials;

    }
}