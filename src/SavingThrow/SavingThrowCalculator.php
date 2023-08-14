<?php

namespace AfmLibre\Pathfinder\SavingThrow;

use AfmLibre\Pathfinder\Entity\Character;

class SavingThrowCalculator
{
    /**
     * @return SavingThrowDto[]
     */
    public function calculate(Character $character,): array
    {
        $savingThrows = [];
        foreach (SavingThrowEnum::cases() as $savingThrowEnum) {
            $specials = [];
            $currentLevel = $character->current_level;
            $abilityName = SavingThrowEnum::abilityLinked($savingThrowEnum);
            $propertyName = $savingThrowEnum->value;
            $savingThrows[] = new SavingThrowDto(
                $propertyName,
                $currentLevel->$propertyName,
                $abilityName,
                Character::getValueModifier($character->$abilityName),
                $specials
            );
        }

        return $savingThrows;
    }
}