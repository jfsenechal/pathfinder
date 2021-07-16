<?php


namespace AfmLibre\Pathfinder\Spell\Handler;

use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\CharacterSpell;
use AfmLibre\Pathfinder\Entity\Spell;
use AfmLibre\Pathfinder\Entity\SpellProfile;
use AfmLibre\Pathfinder\Entity\SpellProfileCharacterSpell;
use AfmLibre\Pathfinder\Repository\CharacterSpellRepository;
use AfmLibre\Pathfinder\Repository\SpellClassRepository;
use AfmLibre\Pathfinder\Repository\SpellProfileCharacterRepository;
use AfmLibre\Pathfinder\Repository\SpellRepository;
use Doctrine\Common\Collections\ArrayCollection;

class SpellProfileHandler
{
    private SpellClassRepository $spellClassRepository;
    private CharacterSpellRepository $characterSpellRepository;
    private SpellRepository $spellRepository;
    private SpellProfileCharacterRepository $spellProfileCharacterRepository;

    public function __construct(
        CharacterSpellRepository $characterSpellRepository,
        SpellClassRepository $spellClassRepository,
        SpellRepository $spellRepository,
        SpellProfileCharacterRepository $spellProfileCharacterRepository
    ) {
        $this->spellClassRepository = $spellClassRepository;
        $this->characterSpellRepository = $characterSpellRepository;
        $this->spellRepository = $spellRepository;
        $this->spellProfileCharacterRepository = $spellProfileCharacterRepository;
    }

    /**
     * @param Character $spellProfile
     * @param array|ArrayCollection|CharacterSpell[] $characterSpells
     */
    public function handle(SpellProfile $spellProfile, iterable $characterSpells = [])
    {
        foreach ($spellProfile->getCharacterSpells() as $characterSpell) {
            $t = new SpellProfileCharacterSpell($spellProfile, $characterSpell);
            $this->spellProfileCharacterRepository->persist($t);
            //$this->spellProfileCharacterRepository->find();
            $spellProfile->addSpellProfileCharacterSpell($t);
        }
        $this->spellProfileCharacterRepository->flush();
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
