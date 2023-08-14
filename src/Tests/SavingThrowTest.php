<?php

use AfmLibre\Pathfinder\Ability\AbilityEnum;
use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\Level;
use AfmLibre\Pathfinder\Modifier\ModifierCalculator;
use AfmLibre\Pathfinder\SavingThrow\SavingThrowCalculator;
use AfmLibre\Pathfinder\SavingThrow\SavingThrowDto;
use AfmLibre\Pathfinder\SavingThrow\SavingThrowEnum;

it(
    'savingThrow class',
    function (
        int $reflex,
        int $dexterity,
        int $fortitude,
        int $constitution,
        int $will,
        int $wisdom,
        array $totals
    ) {
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

        $calculator = Mockery::mock(SavingThrowCalculator::class);
        $calculator->shouldReceive('calculate')->andReturnValues([
            [
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
            ],
        ]);

        $results = $calculator->calculate($character);
        expect($results[0]->total())->toBe($totals[0]);
        expect($results[1]->total())->toBe($totals[1]);
        expect($results[2]->total())->toBe($totals[2]);
    }
)->with('savingThrows');

dataset('savingThrows', [
    'test1' => [
        'reflex' => 0,
        'dexterity' => 12,
        'fortitude' => 2,
        'constitution' => 12,
        'will' => 0,
        'wisdom' => 10,
        'totals' => [1, 3, 0],
    ],
    'test2' => [
        'reflex' => 2,
        'dexterity' => 14,
        'fortitude' => 0,
        'constitution' => 8,
        'will' => 4,
        'wisdom' => 16,
        'totals' => [4, -1, 7],
    ],
]);
