<?php


namespace AfmLibre\Pathfinder\Spell\Handler;

use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\CharacterSpell;
use AfmLibre\Pathfinder\Entity\Spell;
use AfmLibre\Pathfinder\Repository\CharacterSpellRepository;
use AfmLibre\Pathfinder\Repository\SpellClassLevelRepository;
use AfmLibre\Pathfinder\Repository\SpellRepository;

class HandlerCharacterSelection
{
    private SpellClassLevelRepository $spellClassLevelRepository;
    private CharacterSpellRepository $characterSpellRepository;
    private SpellRepository $spellRepository;

    public function __construct(
        CharacterSpellRepository $characterSpellRepository,
        SpellClassLevelRepository $spellClassLevelRepository,
        SpellRepository $spellRepository
    ) {
        $this->spellClassLevelRepository = $spellClassLevelRepository;
        $this->characterSpellRepository = $characterSpellRepository;
        $this->spellRepository = $spellRepository;
    }

    /**
     * @param \AfmLibre\Pathfinder\Entity\Character $character
     * @param array|int[] $spellIds
     */
    public function handle(Character $character, array $spellIds)
    {
        foreach ($spellIds as $spellId) {
            $spell = $this->spellRepository->find($spellId);
            $characterSpell = $this->createCharacterSpell($character, $spell);
            $this->characterSpellRepository->persist($characterSpell);
        }
        $this->characterSpellRepository->flush();
    }

    private function createCharacterSpell(Character $character, Spell $spell): CharacterSpell
    {
        $class = $character->getCharacterClass();
        $level = 0;
        if ($spellLevel = $this->spellClassLevelRepository->searchByClassAndSpell($class, $spell)) {
            $level = $spellLevel->getLevel();
        }

        $characterSpell = new CharacterSpell($character, $spell, $level);

        return $characterSpell;
    }

}
