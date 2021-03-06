<?php


namespace AfmLibre\Pathfinder\Spell\Handler;

use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\CharacterSpell;
use AfmLibre\Pathfinder\Entity\Spell;
use AfmLibre\Pathfinder\Repository\CharacterSpellRepository;
use AfmLibre\Pathfinder\Repository\SpellClassRepository;
use AfmLibre\Pathfinder\Repository\SpellRepository;

class SpellAvailableHandler
{
    private SpellClassRepository $spellClassRepository;
    private CharacterSpellRepository $characterSpellRepository;
    private SpellRepository $spellRepository;

    public function __construct(
        CharacterSpellRepository $characterSpellRepository,
        SpellClassRepository $spellClassRepository,
        SpellRepository $spellRepository
    ) {
        $this->spellClassRepository = $spellClassRepository;
        $this->characterSpellRepository = $characterSpellRepository;
        $this->spellRepository = $spellRepository;
    }

    /**
     * @param Character $character
     * @param array|Spell[] $spells
     */
    public function handle(Character $character, array $spells)
    {
        foreach ($spells as $spell) {
            if (!$this->characterSpellRepository->findByCharacterAndSpell($character, $spell)) {
                $this->characterSpellRepository->persist($this->createCharacterSpell($character, $spell));
            }
        }
        $this->characterSpellRepository->flush();
    }

    public function delete(int $characterSpellId): ?Character
    {
        if ($characterSpell = $this->characterSpellRepository->find($characterSpellId)) {
            $character = $characterSpell->getCharacterPlayer();
            $this->characterSpellRepository->remove($characterSpell);
            $this->characterSpellRepository->flush();

            return $character;
        }

        return null;
    }

    private function createCharacterSpell(Character $character, Spell $spell): CharacterSpell
    {
        $class = $character->getCharacterClass();
        $level = 0;
        if ($spellLevel = $this->spellClassRepository->searchByClassAndSpell($class, $spell)) {
            $level = $spellLevel->getLevel();
        }

        $characterSpell = new CharacterSpell($character, $spell, $level);

        return $characterSpell;
    }

}
