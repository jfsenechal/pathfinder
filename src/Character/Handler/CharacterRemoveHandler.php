<?php

namespace AfmLibre\Pathfinder\Character\Handler;

use AfmLibre\Pathfinder\Character\Repository\CharacterFeatRepository;
use AfmLibre\Pathfinder\Character\Repository\CharacterRepository;
use AfmLibre\Pathfinder\Character\Repository\CharacterSkillRepository;
use AfmLibre\Pathfinder\Character\Repository\CharacterWeaponRepository;
use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Spell\Repository\FavoriteSpellRepository;

class CharacterRemoveHandler
{
    public function __construct(
        private CharacterWeaponRepository $characterWeaponRepository,
        private CharacterFeatRepository $characterFeatRepository,
        private CharacterSkillRepository $characterSkillRepository,
        private FavoriteSpellRepository $favoriteSpellRepository,
        private CharacterRepository $characterRepository
    ) {
    }

    public function remove(Character $character)
    {
        foreach ($this->characterWeaponRepository->findByCharacter($character) as $entity) {
            $this->characterRepository->remove($entity);
        }
        foreach ($this->characterFeatRepository->findByCharacter($character) as $entity) {
            $this->characterRepository->remove($entity);
        }
        foreach ($this->characterSkillRepository->findByCharacter($character) as $entity) {
            $this->characterRepository->remove($entity);
        }
        foreach ($this->favoriteSpellRepository->findByCharacter($character) as $entity) {
            $this->characterRepository->remove($entity);
        }

        $this->characterRepository->remove($character);
        $this->characterRepository->flush();
    }
}