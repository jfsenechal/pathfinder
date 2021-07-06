<?php


namespace AfmLibre\Pathfinder\Classes;


class ClassParser
{
    /**
     * Bar 1, Ens/Mag 1, Prê/Ora 1, Psy 1, Rôd 1
     * @param string $classShort
     */
    public static function getClassName(string $classShort): ?string
    {
        switch ($classShort) {
            case 'Ens/Mag':
                return 'Mage';
            case 'Prê/Ora':
                return 'Pretre';
            case 'Psy':
                return 'Psy';
            case 'Rôd':
                return 'Rodeur';
            case 'x':
                return 'y';
            default;
                return null;
        }

    }

}
