<?php


namespace AfmLibre\Pathfinder\Spell\Handler;

use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\CharacterSpell;
use AfmLibre\Pathfinder\Entity\SpellProfile;
use AfmLibre\Pathfinder\Entity\SpellProfileCharacterSpell;
use AfmLibre\Pathfinder\Repository\CharacterSpellRepository;
use AfmLibre\Pathfinder\Repository\SpellProfileCharacterRepository;
use AfmLibre\Pathfinder\Repository\SpellProfileCharacterSpellRepository;
use AfmLibre\Pathfinder\Spell\Dto\QuantityDto;
use AfmLibre\Pathfinder\Spell\Dto\SpellProfileSelectionDto;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\ChoiceList\ChoiceList;

class SpellProfileHandler
{
    public function __construct(private readonly CharacterSpellRepository $characterSpellRepository, private readonly SpellProfileCharacterSpellRepository $spellProfileCharacterSpellRepository, private readonly SpellProfileCharacterRepository $spellProfileCharacterRepository)
    {
    }

    public function init(SpellProfile $spellProfile): SpellProfileSelectionDto
    {
        $character = $spellProfile->character;
        $characterSpellsSelection = $this->characterSpellRepository->findByCharacter($character);

        $spellProfileCharacterSpells = $this->spellProfileCharacterSpellRepository->findBySpellProfile($spellProfile);

        $characterSpells = array_map(
            fn($spellProfileCharacterSpell) => $spellProfileCharacterSpell->character_spell,
            $spellProfileCharacterSpells
        );

        return new SpellProfileSelectionDto($characterSpells, $characterSpellsSelection);
    }

    public function handle(SpellProfile $spellProfile)
    {
        $originalData = $this->spellProfileCharacterRepository->getOriginalEntityData($spellProfile);
        $spellProfileCharacterSpellsOrigine = $originalData['spell_profile_character_spells'];

        // $changes = array_diff_assoc($toArrayEntity, $originalData);
        $this->toRemove($spellProfileCharacterSpellsOrigine, $spellProfile->getCharacterSpells());

        foreach ($spellProfile->getCharacterSpells() as $characterSpell) {
            if (!($spellProfileCharacterSpell = $this->spellProfileCharacterRepository->findByProfileAndCharacterSpell(
                $spellProfile,
                $characterSpell
            )) instanceof SpellProfileCharacterSpell) {
                $spellProfileCharacterSpell = new SpellProfileCharacterSpell($spellProfile, $characterSpell);
                $this->spellProfileCharacterRepository->persist($spellProfileCharacterSpell);
            }
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
     * @param array|SpellProfileCharacterSpell[] $spellProfileCharacterSpellsOrigine
     * @param array|CharacterSpell[]|ArrayCollection $characterSpellsSelection
     */
    private function toRemove(iterable $spellProfileCharacterSpellsOrigine, iterable $characterSpellsSelection): void
    {
        $idsSelection = array_map(
            fn($characterSpellSelection) => $characterSpellSelection->getSpell()->getId(),
            $characterSpellsSelection->toArray()
        );

        foreach ($spellProfileCharacterSpellsOrigine as $item) {
            $characterSpell = $item->getCharacterSpell();
            if (!in_array($characterSpell->getSpell()->getId(), $idsSelection)) {
                $toRemove[] = $item->getId();
                $this->spellProfileCharacterSpellRepository->remove($item);
            }
        }
    }
}
