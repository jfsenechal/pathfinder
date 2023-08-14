<?php

namespace AfmLibre\Pathfinder\Character\Model;

use AfmLibre\Pathfinder\Ability\AbilityDto;
use AfmLibre\Pathfinder\Armor\ArmorClassDto;
use AfmLibre\Pathfinder\Armor\CmdDto;
use AfmLibre\Pathfinder\Attack\AttackRoll;
use AfmLibre\Pathfinder\Attack\CmbDto;
use AfmLibre\Pathfinder\Entity\Armor;
use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\CharacterFeat;
use AfmLibre\Pathfinder\Entity\Level;
use AfmLibre\Pathfinder\Entity\RaceTrait;
use AfmLibre\Pathfinder\Entity\Spell;
use AfmLibre\Pathfinder\SavingThrow\SavingThrowDto;
use AfmLibre\Pathfinder\Skill\SkillDto;

class CharacterDto
{
    /**
     * @var Spell[]
     */
    public array $spells = [];
    public RaceTrait $raceModifier;
    public Level $currentLevel;
    /**
     * @var AbilityDto[]
     */
    public array $abilities = [];
    /**
     * @var  SavingThrowDto[]
     */
    public array $savingThrows = [];
    /**
     * @var SkillDto[]
     */
    public array $skills = [];

    public ArmorClassDto $armorClass;
    /**
     * @var  AttackRoll[]
     */
    public array $characterWeapons = [];
    /**
     * @var  CharacterFeat[]
     */
    public array $characterFeats = [];
    public CmbDto $cmb;
    public CmdDto $cmd;
    public ?Armor $armor = null;
    public ?Armor $shield = null;

    public function __construct(public readonly Character $character)
    {
    }
}
