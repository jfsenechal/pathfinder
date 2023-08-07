<?php

namespace AfmLibre\Pathfinder\Modifiers;

use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Modifier\ModifierListingEnum;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('pathfinder.modifier_tag', ['lazy' => true])]
interface ModifierInterface
{
    public function isCharacterConcern(Character $character): bool;

    public function useOn(): ModifierListingEnum;

    public function valueModifier(): int;

    public function explain():string;

}