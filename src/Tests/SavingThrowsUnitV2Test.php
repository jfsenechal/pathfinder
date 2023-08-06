<?php

namespace AfmLibre\Pathfinder\Tests;

use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\ClassT;
use AfmLibre\Pathfinder\Entity\Level;
use AfmLibre\Pathfinder\SavingThrow\SavingThrowCalculator;
use PHPUnit\Framework\TestCase;

final class SavingThrowsUnitV2Test extends TestCase
{
    public function testStub(): void
    {
        $calculator = new SavingThrowCalculator();
        $reflex = 0;
        $dexterity = 12;
        $fortitude = 2;
        $constitution = 12;
        $will = 0;
        $wisdom = 10;
        $totals = [1, 3, 0];

        $classT = new ClassT();
        $level = new Level($classT);

        $level->fortitude = $fortitude;
        $level->will = $will;
        $level->reflex = $reflex;

        $character = new Character();
        $character->dexterity = $dexterity;
        $character->wisdom = $wisdom;
        $character->constitution = $constitution;
        $character->current_level = $level;

        $abilities = $calculator->calculate($character);
        $this->assertEquals($abilities[0]->total(), $totals[0]);
        $this->assertEquals($abilities[1]->total(), $totals[1]);
        $this->assertEquals($abilities[2]->total(), $totals[2]);
    }
}