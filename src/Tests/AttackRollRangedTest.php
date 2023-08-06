<?php

use AfmLibre\Pathfinder\Ancestry\SizeEnum;
use AfmLibre\Pathfinder\Attack\AttackCalculator;
use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\Level;
use AfmLibre\Pathfinder\Entity\Weapon;

it(
    'check bonus ranged',
    function (int $bab, int $dexterity, int $rangePenalty, SizeEnum $sizeEnum, int $total) {
        $character = $this->getMockBuilder(Character::class)
            ->disableOriginalConstructor()
            ->getMock();
        $weapon = $this->getMockBuilder(Weapon::class)
            ->disableOriginalConstructor()
            ->getMock();

        $level = $this->getMockBuilder(Level::class)
            ->disableOriginalConstructor()
            ->getMock();

        $level->bab = $bab;

        $character->dexterity = $dexterity;
        $weapon->ranged = $rangePenalty;

        $weapon->distance = true;
        $character->current_level = $level;

        $attack = AttackCalculator::createAttackRoll($character, $weapon, $sizeEnum);

        expect($attack->total())->toBe($total);
    }
)->with('weaponsRanged');

dataset('weaponsRanged', [
    'medium' => [
        'bab' => 3,
        'dexterity' => 10,
        'rangePenalty' => 0,
        'size' => SizeEnum::Medium,
        'total' => 3,
    ],
    'tiny' => [
        'bab' => 3,
        'dexterity' => 10,
        'rangePenalty' => 0,
        'size' => SizeEnum::Tiny,
        'total' => 1,
    ],
    'deux' => [
        'bab' => 2,
        'dexterity' => 10,
        'rangePenalty' => 0,
        'size' => SizeEnum::Medium,
        'total' => 2,
    ],
    'trois' => [
        'bab' => 4,
        'dexterity' => 10,
        'rangePenalty' => 0,
        'size' => SizeEnum::Medium,
        'total' => 4,
    ],
    'arc' => [
        'bab' => 4,
        'dexterity' => 10,
        'rangePenalty' => 0,
        'size' => SizeEnum::Medium,
        'total' => 4,
    ],
]);
