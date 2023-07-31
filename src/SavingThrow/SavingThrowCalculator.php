<?php

namespace AfmLibre\Pathfinder\SavingThrow;

use AfmLibre\Pathfinder\Ability\AbilityDto;
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

    public function run(Character $character,): array
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

        $abilities = [];
        $currentLevel = $character->current_level;
        $abilities[] = new AbilityDto(
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

        $abilities[] = new AbilityDto(
            'Vigueur',
            $currentLevel->fortitude,
            'Constitution',
            Character::getValueModifier($character->constitution),
            $specials
        );
        $specials = [];
        $abilities[] = new AbilityDto(
            'Volonté',
            $currentLevel->will,
            'Sagesse',
            Character::getValueModifier($character->wisdom),
            $specials
        );

        return $abilities;
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