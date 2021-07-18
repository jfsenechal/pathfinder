<?php


namespace AfmLibre\Pathfinder\Spell\Handler;

use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\CharacterSpell;
use AfmLibre\Pathfinder\Entity\SpellProfile;
use AfmLibre\Pathfinder\Entity\SpellProfileCharacterSpell;
use AfmLibre\Pathfinder\Repository\CharacterSpellRepository;
use AfmLibre\Pathfinder\Repository\SpellProfileCharacterRepository;
use AfmLibre\Pathfinder\Repository\SpellProfileCharacterSpellRepository;
use Doctrine\Common\Collections\ArrayCollection;

class SpellProfileHandler
{
    private CharacterSpellRepository $characterSpellRepository;
    private SpellProfileCharacterRepository $spellProfileCharacterRepository;
    private SpellProfileCharacterSpellRepository $spellProfileCharacterSpellRepository;

    public function __construct(
        CharacterSpellRepository $characterSpellRepository,
        SpellProfileCharacterSpellRepository $spellProfileCharacterSpellRepository,
        SpellProfileCharacterRepository $spellProfileCharacterRepository
    ) {
        $this->characterSpellRepository = $characterSpellRepository;
        $this->spellProfileCharacterRepository = $spellProfileCharacterRepository;
        $this->spellProfileCharacterSpellRepository = $spellProfileCharacterSpellRepository;
    }

    /**
     * @param \AfmLibre\Pathfinder\Entity\SpellProfile $spellProfile
     */
    public function handle(SpellProfile $spellProfile)
    {
        $originalData = $this->spellProfileCharacterRepository->getOriginalEntityData($spellProfile);
        $spellProfileCharacterSpellsOrigine = $originalData['spell_profile_character_spells'];

        // $changes = array_diff_assoc($toArrayEntity, $originalData);
        $this->toRemove($spellProfileCharacterSpellsOrigine, $spellProfile->getCharacterSpells());

        foreach ($spellProfile->getCharacterSpells() as $characterSpell) {
            if (!$spellProfileCharacterSpell = $this->spellProfileCharacterRepository->findByProfileAndCharacterSpell(
                $spellProfile,
                $characterSpell
            )) {
                $spellProfileCharacterSpell = new SpellProfileCharacterSpell($spellProfile, $characterSpell);
                $this->spellProfileCharacterRepository->persist($spellProfileCharacterSpell);
            }
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

    /**
     * @param array|SpellProfileCharacterSpell[] $spellProfileCharacterSpellsOrigine
     * @param array|CharacterSpell[]|ArrayCollection $characterSpellsSelection
     */
    private function toRemove(iterable $spellProfileCharacterSpellsOrigine, iterable $characterSpellsSelection): void
    {
        $idsSelection = array_map(
            function ($characterSpellSelection) {
                return $characterSpellSelection->getSpell()->getId();
            },
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
