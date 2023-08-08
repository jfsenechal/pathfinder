<?php

namespace AfmLibre\Pathfinder\Modifiers;

use AfmLibre\Pathfinder\Modifier\ModifierListingEnum;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

class ModifiersHandler
{
    /**
     * @param ModifierInterface[] $modifiers
     */
    public function __construct(#[TaggedIterator(ModifierInterface::class)] private iterable $modifiers)
    {

    }

    /**
     * @param ModifierListingEnum $modifierListingEnum
     * @return ModifierInterface[]
     */
    public function locate(ModifierListingEnum $modifierListingEnum): array
    {
        $modifiers = [];
        foreach ($this->modifiers as $modifier) {
            if ($modifier->useOn() !== $modifierListingEnum) {
                continue;
            }
            $modifiers[] = $modifier;
        }

        return $modifiers;
    }

}