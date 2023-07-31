<?php

namespace AfmLibre\Pathfinder\Ability;

use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\Feat;
use AfmLibre\Pathfinder\Entity\Modifier;
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

    public function calculate(Character $character): array
    {
        $abilities = [];
        foreach (ModifierEnum::abilities() as $modifierEnum) {
            $basespecials = $this->findSpecials($character, $modifierEnum);
            $property = strtolower($modifierEnum->value);
            $specials = [];
            $abilities[] = new AbilityDto(
                $modifierEnum->value,
                $character->$property,
                AbilityEnum::valueModifier($character->$property),
                $basespecials,
                $specials
            );
        }

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