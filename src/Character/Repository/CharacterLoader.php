<?php

namespace AfmLibre\Pathfinder\Character\Repository;

use AfmLibre\Pathfinder\Ability\AbilityCalculator;
use AfmLibre\Pathfinder\Ancestry\SizeEnum;
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
        private readonly CharacterArmorRepository $characterArmorRepository,
        private readonly CharacterWeaponRepository $characterWeaponRepository,
        private readonly CharacterFeatRepository $characterFeatRepository,
        private readonly AbilityCalculator $abilityCalculator,
        private readonly SavingThrowCalculator $savingThrowCalculator,
        private readonly SkillCalculator $skillCalculator,
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

        $characterDto->characterArmors = $this->characterArmorRepository->findByCharacter($character);
        $characterDto->armorClass = ArmorCalculator::createArmorAbility(
            $character,
            $characterDto->characterArmors,
            SizeEnum::SIZE_MIDDLE
        );

        $weapons = $this->characterWeaponRepository->findByCharacter($character);
        $characterDto->characterWeapons = array_map(function (CharacterWeapon $characterWeapon) use ($character) {
            $weapon = $characterWeapon->weapon;
            $characterWeapon->damageRoll = AttackCalculator::createDamageAbility($character, $weapon);
            $characterWeapon->attackRoll = AttackCalculator::createAttackRoll(
                $character,
                $weapon,
                SizeEnum::SIZE_MIDDLE
            );

            return $characterWeapon;
        }, $weapons);

        $characterDto->characterFeats = $this->characterFeatRepository->findByCharacter($character);

        $characterDto->cmb = AttackCalculator::createCmb($character, SizeEnum::SIZE_MIDDLE);
        $characterDto->cmd = ArmorCalculator::createCmd($character, SizeEnum::SIZE_MIDDLE);

        return $characterDto;
    }
}