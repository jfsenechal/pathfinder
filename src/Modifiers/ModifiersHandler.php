<?php

namespace AfmLibre\Pathfinder\Modifiers;

use AfmLibre\Pathfinder\Modifier\ModifierListingEnum;

class ModifiersHandler
{
    /**
     * @param ModifierInterface[] $modifiers
     */
    public function __construct(private $modifiers)
    {

    }

    public function t(ModifierListingEnum $modifierListingEnum)
    {
        foreach ($this->modifiers as $modifier) {
            if ($modifier->useOn() !== $modifierListingEnum) {
                continue;
            }
            dump($modifier->useOn()->name);
        }
    }

}