<?php

namespace AfmLibre\Pathfinder\Tests;

use AfmLibre\Pathfinder\Ancestry\SizeEnum;
use PHPUnit\Framework\TestCase;

class SizeModifierTest extends TestCase
{
    public function testModifierValue(): void
    {
        $this->assertEquals(-8, SizeEnum::valueModifier(SizeEnum::Fine));
        $this->assertEquals(0, SizeEnum::valueModifier(SizeEnum::Medium));
        $this->assertEquals(4, SizeEnum::valueModifier(SizeEnum::Gargantuan));
    }
}