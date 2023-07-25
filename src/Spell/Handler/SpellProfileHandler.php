<?php


namespace AfmLibre\Pathfinder\Spell\Handler;

use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\CharacterSpell;
use AfmLibre\Pathfinder\Entity\SpellProfile;
use AfmLibre\Pathfinder\Entity\SpellProfileCharacter;
use AfmLibre\Pathfinder\Repository\CharacterSpellRepository;
use AfmLibre\Pathfinder\Repository\SpellProfileCharacterRepository;
use AfmLibre\Pathfinder\Spell\Dto\SpellProfileSelectionDto;
use Doctrine\Common\Collections\ArrayCollection;

class SpellProfileHandler
{
    public function __construct(
        private readonly CharacterSpellRepository $characterSpellRepository,
        private readonly SpellProfileCharacterRepository $spellProfileCharacterRepository,
    ) {
    }

    public function init(SpellProfile $spellProfile): SpellProfileSelectionDto
    {
        $character = $spellProfile->character;
        $characterSpellsSelection = $this->characterSpellRepository->findByCharacter($character);

        $spellProfileCharacters = $this->spellProfileCharacterRepository->findBySpellProfile($spellProfile);

        $characterSpells = array_map(
            fn($spellProfileCharacter) => $spellProfileCharacter->character_spell,
            $spellProfileCharacters
        );

        return new SpellProfileSelectionDto($characterSpells, $characterSpellsSelection);
    }

    public function handle(SpellProfile $spellProfile)
    {
        $originalData = $this->spellProfileCharacterRepository->getOriginalEntityData($spellProfile);
        $spellProfileCharactersOrigine = $originalData['spell_profile_character_spells'];

        // $changes = array_diff_assoc($toArrayEntity, $originalData);
        $this->toRemove($spellProfileCharactersOrigine, $spellProfile->getCharacterSpells());

        foreach ($spellProfile->getCharacterSpells() as $characterSpell) {
            if (!($spellProfileCharacter = $this->spellProfileCharacterRepository->findByProfileAndCharacterSpell(
                    $spellProfile,
                    $characterSpell
                )) instanceof SpellProfileCharacter) {
                $spellProfileCharacter = new SpellProfileCharacter($spellProfile, $characterSpell);
                $this->spellProfileCharacterRepository->persist($spellProfileCharacter);
            }
            $spellProfileCharacter->quantity = 5;
        }
        $this->spellProfileCharacterRepository->flush();
    }

    public function delete(int $characterSpellId): ?Character
    {
        if (($characterSpell = $this->characterSpellRepository->find($characterSpellId)) instanceof CharacterSpell) {
            $character = $characterSpell->character;
            $this->characterSpellRepository->remove($characterSpell);
            $this->characterSpellRepository->flush();

            return $character;
        }

        return null;
    }

    /**
     * @param array|SpellProfileCharacter[] $spellProfileCharactersOrigine
     * @param array|CharacterSpell[]|ArrayCollection $characterSpellsSelection
     */
    private function toRemove(iterable $spellProfileCharactersOrigine, iterable $characterSpellsSelection): void
    {
        $idsSelection = array_map(
            fn($characterSpellSelection) => $characterSpellSelection->getSpell()->getId(),
            $characterSpellsSelection->toArray()
        );

        foreach ($spellProfileCharactersOrigine as $item) {
            $characterSpell = $item->getCharacterSpell();
            if (!in_array($characterSpell->getSpell()->getId(), $idsSelection)) {
                $toRemove[] = $item->getId();
                $this->spellProfileCharacterRepository->remove($item);
            }
        }
    }
}
