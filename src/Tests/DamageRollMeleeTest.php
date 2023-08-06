<?php

namespace AfmLibre\Pathfinder\Tests;

use AfmLibre\Pathfinder\Attack\AttackCalculator;
use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\Level;
use AfmLibre\Pathfinder\Entity\Weapon;

beforeEach(function () {

});

it('check bonus melee', function (int $bab, int $strength, bool $twoHanded, bool $leadingHand, int $total) {
    $character = $this->getMockBuilder(Character::class)
        ->disableOriginalConstructor()
        ->getMock();
    $weapon = $this->getMockBuilder(Weapon::class)
        ->disableOriginalConstructor()
        ->getMock();

    $level = $this->getMockBuilder(Level::class)
        ->disableOriginalConstructor()
        ->getMock();

    $character->strength = $strength;
    $level->bab = $bab;
    $character->current_level = $level;
    $weapon->twoHanded = $twoHanded;
    $weapon->leadingHand = $leadingHand;

    $attack = AttackCalculator::createDamageRoll($character, $weapon);

    expect($attack->bonusDamage())->toBe($total);
})->with('weaponsMelee');

dataset('weaponsMelee', [
    'medium' => [
        'bab' => 3,
        'strength' => 12,
        'twoHanded' => false,
        'leadingHand' => true,
        'total' => 4,
    ],
    'tiny' => [
        'bab' => 3,
        'strength' => 12,
        'twoHanded' => false,
        'leadingHand' => true,
        'total' => 2,
    ],
    'deux' => [
        'bab' => 2,
        'strength' => 18,
        'twoHanded' => false,
        'leadingHand' => true,
        'total' => 6,
    ],
    'trois' => [
        'bab' => 4,
        'strength' => 8,
        'twoHanded' => false,
        'leadingHand' => true,
        'total' => 3,
    ],
]);
