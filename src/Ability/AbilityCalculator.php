<?php

namespace AfmLibre\Pathfinder\Ability;

use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\Feat;
use AfmLibre\Pathfinder\Entity\Race;
use AfmLibre\Pathfinder\Modifier\ModifierEnum;
use AfmLibre\Pathfinder\Repository\CharacterFeatRepository;
use AfmLibre\Pathfinder\Repository\ModifierRepository;

class AbilityCalculator
{
    public function __construct(
        private ModifierRepository $modifierRepository,
        private CharacterFeatRepository $characterFeatRepository
    ) {
    }

    public function abilities(Character $character,): array
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

    public function abilities6Score(Character $character): array
    {
        $abilities = [];
        foreach (ModifierEnum::abilities() as $modifierEnum) {
            $basespecials = $this->findSpecials($character, $modifierEnum);
            $property = strtolower($modifierEnum->value);
            $specials = [];
            $abilities[] = new Ability6Dto(
                $modifierEnum->value,
                $character->$property,
                AbilityEnum::valueModifier($character->$property),
                $basespecials,
                $specials
            );
        }

        return $abilities;
    }

    private function findSpecials(Character $character, ModifierEnum $modifierEnum)
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