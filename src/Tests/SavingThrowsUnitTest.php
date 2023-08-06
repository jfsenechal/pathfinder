<?php

namespace AfmLibre\Pathfinder\Tests;

use AfmLibre\Pathfinder\Ability\AbilityEnum;
use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\Level;
use AfmLibre\Pathfinder\Modifier\ModifierCalculator;
use AfmLibre\Pathfinder\SavingThrow\SavingThrowCalculator;
use AfmLibre\Pathfinder\SavingThrow\SavingThrowDto;
use AfmLibre\Pathfinder\SavingThrow\SavingThrowEnum;
use PHPUnit\Framework\TestCase;

final class SavingThrowsUnitTest extends TestCase
{
    public function testStub(): void
    {
        $calculator = $this->createStub(SavingThrowCalculator::class);
        $reflex = 0;
        $dexterity = 12;
        $fortitude = 2;
        $constitution = 12;
        $will = 0;
        $wisdom = 10;
        $totals = [1, 3, 0];

        $level = $this->getMockBuilder(Level::class)
            ->disableOriginalConstructor()
            ->getMock();
        $level->fortitude = $fortitude;
        $level->will = $will;
        $level->reflex = $reflex;

        $character = $this->getMockBuilder(Character::class)
            ->disableOriginalConstructor()
            ->getMock();
        $character->dexterity = $dexterity;
        $character->wisdom = $wisdom;
        $character->constitution = $constitution;
        $character->current_level = $level;

        $calculator->method('calculate')
            ->willReturn([
                    new SavingThrowDto(
                        SavingThrowEnum::Reflex->value,
                        $reflex,
                        AbilityEnum::ABILITY_DEXTERITY->value,
                        ModifierCalculator::abilityValueModifier($dexterity),
                    ),
                    new SavingThrowDto(
                        SavingThrowEnum::Fortitude->value,
                        $fortitude,
                        AbilityEnum::ABILITY_CONSTITUTION->value,
                        ModifierCalculator::abilityValueModifier($constitution),
                    ),
                    new SavingThrowDto(
                        SavingThrowEnum::Will->value,
                        $will,
                        AbilityEnum::ABILITY_WISDOM->value,
                        ModifierCalculator::abilityValueModifier($wisdom),
                    ),
                ]
            );

        $abilities = $calculator->calculate($character);
        $this->assertEquals($abilities[0]->total(), $totals[0]);
        $this->assertEquals($abilities[1]->total(), $totals[1]);
        $this->assertEquals($abilities[2]->total(), $totals[2]);
    }
}