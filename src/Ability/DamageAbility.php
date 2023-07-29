<?php

namespace AfmLibre\Pathfinder\Ability;

class DamageAbility
{
    public function __construct(
        readonly string $name,
        readonly int $bab,
        readonly string $weaponDamage,
        readonly int $strength,
        readonly bool $twoHanded,
        readonly bool $leadingHand,
    ) {
    }

    /**
     * !Arme utilisée dans la main non-directrice
     * il n’ajoute que la moitié de son bonus de Force aux dégâts infligés.
     * Dans le cas d’un malus de Force, celui-ci s’applique en totalité.
     * !Arme à deux mains.il ajoute une fois et demie son bonus normal (les malus de Force ne sont pas multipliés)
     *  arme légère à deux mains, il ne bénéficie pas de ce bonus.
     */
    public function total(): int
    {
        $strength = $this->strength;

        if ($this->twoHanded) {
            if ($strength > 0) {
                $strength = $strength * 1.5;
            }
        } else {
            if (!$this->leadingHand) {
                if ($strength > 0) {
                    $strength = $strength / 2;
                }
            }
        }

        return $this->bab + $strength;
    }
}