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
    public function __construct(private readonly CharacterSpellRepository $characterSpellRepository, private readonly SpellClassRepository $spellClassRepository, private readonly SpellRepository $spellRepository)
    {
    }

    /**
     * @param array|Spell[] $spells
     */
    public function handle(Character $character, array $spells)
    {
        foreach ($spells as $spell) {
            if (!$this->characterSpellRepository->findByCharacterAndSpell($character, $spell) instanceof \AfmLibre\Pathfinder\Entity\CharacterSpell) {
                $this->characterSpellRepository->persist($this->createCharacterSpell($character, $spell));
            }
        }
        $this->characterSpellRepository->flush();
    }

    public function delete(int $characterSpellId): ?Character
    {
        if (($characterSpell = $this->characterSpellRepository->find($characterSpellId)) instanceof \AfmLibre\Pathfinder\Entity\CharacterSpell) {
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
        if (($spellLevel = $this->spellClassRepository->searchByClassAndSpell($class, $spell)) instanceof \AfmLibre\Pathfinder\Entity\SpellClass) {
            $level = $spellLevel->getLevel();
        }

        return new CharacterSpell($character, $spell, $level);
    }

}
