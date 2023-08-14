<?php

namespace AfmLibre\Pathfinder\Ability;

use AfmLibre\Pathfinder\Character\Repository\CharacterFeatRepository;
use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\Feat;
use AfmLibre\Pathfinder\Entity\Modifier;
use AfmLibre\Pathfinder\Entity\Race;
use AfmLibre\Pathfinder\Modifier\ModifierCalculator;
use AfmLibre\Pathfinder\Modifier\ModifierListingEnum;
use AfmLibre\Pathfinder\Modifier\Repository\ModifierRepository;

class AbilityCalculator
{
    public function __construct(
        private readonly ModifierRepository $modifierRepository,
        private readonly CharacterFeatRepository $characterFeatRepository
    ) {
    }

    /**
     * @return AbilityDto[]
     */
    public function calculate(Character $character): array
    {
        $abilities = [];
        foreach (ModifierListingEnum::abilities() as $modifierEnum) {
            $basespecials = $this->findSpecials($character, $modifierEnum);
            $propertyName = $modifierEnum->value;
            $specials = [];
            $abilities[] = new AbilityDto(
                $modifierEnum->value,
                $character->$propertyName,
                ModifierCalculator::abilityValueModifier($character->$propertyName),
                $basespecials,
                $specials
            );
        }

        return $abilities;
    }

    /**
     * @return Modifier[]
     */
    private function findSpecials(Character $character, ModifierListingEnum $modifierEnum): array
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