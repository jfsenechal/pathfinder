<?php

use AfmLibre\Pathfinder\Ancestry\SizeEnum;
use AfmLibre\Pathfinder\Armor\ArmorCalculator;
use AfmLibre\Pathfinder\Entity\Armor;
use AfmLibre\Pathfinder\Entity\Character;

it(
    'savingThrow class',
    function (
        bool $withArmor,
        int $armorBonus,
        int $armorDexterityMax,
        int $dexterity,
        SizeEnum $sizeEnum,
        int $total
    ) {


        $character = \Mockery::mock(Character::class);

        $service = Mockery::mock('SavingThrowCalculator');
        $service->shouldReceive('calculate')
            ->times(1)
            ->andReturn(10, 12, 14);

        $temperature = new Temperature($service);

        $level = $this->getMockBuilder(Level::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->assertEquals(12, $temperature->average());
        $character = $this->getMockBuilder(Character::class)
            ->disableOriginalConstructor()
            ->getMock();
        $character->current_level = $level;

        $attack = SavingThrowCalculator::createArmorAbility($character, $sizeEnum);

        expect($attack->ac())->toBe($total);
    }
)->with('armors');

dataset('armors', [
    'armorNormal' => [
        'armor' => true,
        'armorBonus' => +4,
        'armorDexterityMax' => +2,
        'dexterity' => 12,//+1
        'size' => SizeEnum::Medium,
        'total' => 15,
    ],
    'armorDex0' => [
        'armor' => true,
        'armorBonus' => +4,
        'armorDexterityMax' => +2,
        'dexterity' => 10,//+0
        'size' => SizeEnum::Medium,
        'total' => 14,
    ],
    'armorDexNegative' => [
        'armor' => true,
        'armorBonus' => +4,
        'armorDexterityMax' => +2,
        'dexterity' => 6,//-2
        'size' => SizeEnum::Medium,
        'total' => 12,
    ],
    'dexMaxOverload' => [
        'armor' => true,
        'armorBonus' => +4,
        'armorDexterityMax' => +2,
        'dexterity' => 18,//+4
        'size' => SizeEnum::Medium,
        'total' => 16,
    ],
    'dexMaxNotOverload' => [
        'armor' => true,
        'armorBonus' => +4,
        'armorDexterityMax' => +4,
        'dexterity' => 18,//+4
        'size' => SizeEnum::Medium,
        'total' => 18,
    ],
    'noArmor' => [
        'armor' => false,
        'armorBonus' => +4,
        'armorDexterityMax' => +4,
        'dexterity' => 14,//+2
        'size' => SizeEnum::Medium,
        'total' => 12,
    ],
]);