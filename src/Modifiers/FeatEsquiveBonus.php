<?php

namespace AfmLibre\Pathfinder\Modifiers;


use AfmLibre\Pathfinder\Character\Repository\CharacterFeatRepository;
use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Modifier\ModifierListingEnum;

class FeatEsquiveBonus implements ModifierInterface
{
    public function __construct(private CharacterFeatRepository $characterFeatRepository)
    {

    }

    public function isCharacterConcern(Character $character): bool
    {
        $feats = $this->characterFeatRepository->findByCharacter($character);

        return count(array_filter($feats, fn($feat) => $feat->feat->name == 'Esquive')) > 0;
    }

    public function useOn(): ModifierListingEnum
    {
        return ModifierListingEnum::ARMOR_CLASS;
    }

    public function valueModifier(): int
    {
        return +1;
    }

    public function explain(): string
    {
        return 'Feat esquive give +1 to class armor';
    }
}