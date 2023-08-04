<?php

namespace AfmLibre\Pathfinder\SavingThrow;

use AfmLibre\Pathfinder\Character\Repository\CharacterFeatRepository;
use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\Feat;
use AfmLibre\Pathfinder\Entity\Modifier;
use AfmLibre\Pathfinder\Entity\Race;
use AfmLibre\Pathfinder\Modifier\ModifierListingEnum;
use AfmLibre\Pathfinder\Modifier\Repository\ModifierRepository;

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
        $savingThrows = [];
        foreach (SavingThrowEnum::cases() as $savingThrowEnum) {
            $specials = [];
            $currentLevel = $character->current_level;
            $savingThrows[] = new SavingThrowDto(
                $savingThrowEnum->value,
                $currentLevel->reflex,
                SavingThrowEnum::ability($savingThrowEnum)->value,
                Character::getValueModifier($character->dexterity),
                $specials
            );
        }

        return $savingThrows;
    }

    /**
     * @param Character $character
     * @param ModifierListingEnum $modifierEnum
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