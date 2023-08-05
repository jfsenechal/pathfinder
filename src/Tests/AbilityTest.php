<?php

namespace AfmLibre\Pathfinder\Tests;

use AfmLibre\Pathfinder\Ability\AbilityEnum;
use PHPUnit\Framework\TestCase;

class AbilityTest extends TestCase
{
    public function testAbilityByNameFr(): void
    {
        $this->assertEquals(AbilityEnum::ABILITY_CHARISMA, AbilityEnum::returnByNameFr('Charisme'));
        $this->assertEquals(AbilityEnum::ABILITY_INTELLIGENCE, AbilityEnum::returnByNameFr('Intelligence'));
        $this->assertEquals(AbilityEnum::ABILITY_DEXTERITY, AbilityEnum::returnByNameFr('Dextérité'));
        $this->assertEquals(null, AbilityEnum::returnByNameFr('Bad name'));
    }
}
