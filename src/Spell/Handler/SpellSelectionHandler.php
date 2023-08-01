<?php


namespace AfmLibre\Pathfinder\Spell\Handler;

use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\CharacterSpell;
use AfmLibre\Pathfinder\Entity\Spell;
use AfmLibre\Pathfinder\Entity\ClassSpell;
use AfmLibre\Pathfinder\Repository\CharacterSpellRepository;
use AfmLibre\Pathfinder\Repository\ClassSpellRepository;

class SpellSelectionHandler
{
    public function __construct(
        private readonly CharacterSpellRepository $characterSpellRepository,
        private readonly ClassSpellRepository $classSpellRepository,
    ) {
    }

    /**
     * @param array|Spell[] $spells
     */
    public function handle(Character $character, array $spells)
    {
        foreach ($spells as $spell) {
            if (!$this->characterSpellRepository->findByCharacterAndSpell(
                $character,
                $spell
            ) instanceof CharacterSpell) {
                $this->characterSpellRepository->persist($this->createCharacterSpell($character, $spell));
            }
        }
        $this->characterSpellRepository->flush();
    }

    public function delete(int $characterSpellId): ?Character
    {
        if (($characterSpell = $this->characterSpellRepository->find(
            $characterSpellId
        )) instanceof CharacterSpell) {
            $character = $characterSpell->character;
            $this->characterSpellRepository->remove($characterSpell);
            $this->characterSpellRepository->flush();

            return $character;
        }

        return null;
    }

    private function createCharacterSpell(Character $character, Spell $spell): CharacterSpell
    {
        $class = $character->classT;
        $level = 0;
        if (($spellLevel = $this->classSpellRepository->searchByClassAndSpell(
            $class,
            $spell
        )) instanceof ClassSpell) {
            $level = $spellLevel->level;
        }

        return new CharacterSpell($character, $spell, $level);
    }
}
