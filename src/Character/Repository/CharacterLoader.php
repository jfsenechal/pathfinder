<?php

namespace AfmLibre\Pathfinder\Character\Repository;

use AfmLibre\Pathfinder\Ability\AbilityCalculator;
use AfmLibre\Pathfinder\Armor\ArmorCalculator;
use AfmLibre\Pathfinder\Attack\AttackCalculator;
use AfmLibre\Pathfinder\Character\Model\CharacterDto;
use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\CharacterWeapon;
use AfmLibre\Pathfinder\Race\Repository\RaceTraitRepository;
use AfmLibre\Pathfinder\SavingThrow\SavingThrowCalculator;
use AfmLibre\Pathfinder\Skill\SkillCalculator;
use AfmLibre\Pathfinder\Spell\Repository\FavoriteSpellRepository;
use AfmLibre\Pathfinder\Spell\Utils\SpellUtils;

class CharacterLoader
{
    public function __construct(
        private readonly FavoriteSpellRepository $characterSpellRepository,
        private readonly RaceTraitRepository $raceTraitRepository,
        private readonly CharacterWeaponRepository $characterWeaponRepository,
        private readonly CharacterFeatRepository $characterFeatRepository,
        private readonly AbilityCalculator $abilityCalculator,
        private readonly SavingThrowCalculator $savingThrowCalculator,
        private readonly SkillCalculator $skillCalculator,
        private readonly AttackCalculator $attackCalculator,
        private readonly ArmorCalculator $armorCalculator
    ) {
    }

    public function load(Character $character): CharacterDto
    {
        $characterDto = new CharacterDto($character);

        $characterSpells = $this->characterSpellRepository->findByCharacter($character);
        $characterDto->spells = SpellUtils::groupByLevel($characterSpells);

        $characterDto->raceModifier = $this->raceTraitRepository->findOneByRaceAndName(
            $character->race,
            "Caractéristiques"
        );
        $characterDto->currentLevel = $character->current_level;

        $characterDto->abilities = $this->abilityCalculator->calculate($character);
        $characterDto->savingThrows = $this->savingThrowCalculator->calculate($character);
        $characterDto->skills = $this->skillCalculator->calculate($character);

        $characterDto->armor = $character->armor;
        $characterDto->shield = $character->shield;
        $characterDto->armorClass = $this->armorCalculator->createArmorAbility(
            $character,
            $character->sizeType
        );

        $weapons = $this->characterWeaponRepository->findByCharacter($character);
        $characterDto->characterWeapons = array_map(function (CharacterWeapon $characterWeapon) use ($character) {
            $weapon = $characterWeapon->weapon;
            $characterWeapon->damageRoll = AttackCalculator::createDamageRoll($character, $weapon);
            $characterWeapon->attackRoll = AttackCalculator::createAttackRoll(
                $character,
                $weapon,
                $character->sizeType
            );

            return $characterWeapon;
        }, $weapons);

        $characterDto->characterFeats = $this->characterFeatRepository->findByCharacter($character);

        $characterDto->cmb = $this->attackCalculator->createCmb($character, $character->sizeType);
        $characterDto->cmd = $this->armorCalculator->createCmd($character, $character->sizeType);

        return $characterDto;
    }
}
