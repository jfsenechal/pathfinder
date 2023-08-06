<?php

use AfmLibre\Pathfinder\Ancestry\SizeEnum;
use AfmLibre\Pathfinder\Attack\AttackCalculator;
use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\Level;
use AfmLibre\Pathfinder\Entity\Weapon;

it('check bonus melee', function (int $bab, int $strength, SizeEnum $sizeEnum, int $total) {
    $character = $this->getMockBuilder(Character::class)
        ->disableOriginalConstructor()
        ->getMock();
    $weapon = $this->getMockBuilder(Weapon::class)
        ->disableOriginalConstructor()
        ->getMock();

    $level = $this->getMockBuilder(Level::class)
        ->disableOriginalConstructor()
        ->getMock();

    $client = Mockery::mock(Weapon::class);
    $client->shouldReceive('post');

    $character->strength = $strength;
    $level->bab = $bab;
    $character->current_level = $level;

    $attack = AttackCalculator::createAttackRoll($character, $weapon, $sizeEnum);

    expect($attack->bonusAttack())->toBe($total);
})->with('weaponsMelee');

dataset('weaponsMelee', [
    'medium' => [
        'bab' => 3,
        'strength' => 12,
        'size' => SizeEnum::Medium,
        'total' => 4,
    ],
    'tiny' => [
        'bab' => 3,
        'strength' => 12,
        'size' => SizeEnum::Tiny,
        'total' => 2,
    ],
    'deux' => [
        'bab' => 2,
        'strength' => 18,
        'size' => SizeEnum::Medium,
        'total' => 6,
    ],
    'trois' => [
        'bab' => 4,
        'strength' => 8,
        'size' => SizeEnum::Medium,
        'total' => 3,
    ],
]);
