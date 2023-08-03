<?php


namespace AfmLibre\Pathfinder\Spell\Handler;

use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\FavoriteSpell;
use AfmLibre\Pathfinder\Entity\Spell;
use AfmLibre\Pathfinder\Entity\ClassSpell;
use AfmLibre\Pathfinder\Repository\FavoriteSpellRepository;
use AfmLibre\Pathfinder\Repository\ClassSpellRepository;

class FavoriteSpellHandler
{
    public function __construct(
        private readonly FavoriteSpellRepository $characterSpellRepository,
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
            ) instanceof FavoriteSpell) {
                $this->characterSpellRepository->persist($this->createFavoriteSpell($character, $spell));
            }
        }
        $this->characterSpellRepository->flush();
    }

    public function delete(int $characterSpellId): ?Character
    {
        if (($characterSpell = $this->characterSpellRepository->find(
            $characterSpellId
        )) instanceof FavoriteSpell) {
            $character = $characterSpell->character;
            $this->characterSpellRepository->remove($characterSpell);
            $this->characterSpellRepository->flush();

            return $character;
        }

        return null;
    }

    private function createFavoriteSpell(Character $character, Spell $spell): FavoriteSpell
    {
        $class = $character->classT;
        $level = 0;
        if (($spellLevel = $this->classSpellRepository->searchByClassAndSpell(
            $class,
            $spell
        )) instanceof ClassSpell) {
            $level = $spellLevel->level;
        }

        return new FavoriteSpell($character, $spell, $level);
    }
}
