<?php


namespace AfmLibre\Pathfinder\Classes;

class ClassParser
{
    /**
     * Bar 1, Ens/Mag 1, Prê/Ora 1, Psy 1, Rôd 1
     */
    public static function getClassName(string $classShort): ?string
    {
        $names = [
            '' => 'Adepte',
            'Alc' => 'Alchimiste',
            'Ant' => 'Antipaladin',
            '' => 'Arcaniste',
            '' => 'Archer-mage',
            '' => 'Arpenteur d\'horizon',
            '' => 'Assassin',
            'Bar' => 'Barbare',
            '' => 'Barde',
            '' => 'Bretteur',
            'Chm' => 'Chaman',
            '' => 'Champion occultiste',
            '' => 'Chasseur',
            '' => 'Chasseur de vampire',
            '' => 'Chevalier',
            '' => 'Chroniqueur',
            '' => 'Cinétiste',
            'Con' => 'Conjurateur',
            '' => 'Disciple draconien',
            'Dru' => 'Druide',
            '' => 'Duelliste',
            '' => 'Enquêteur',
            'Ens/Mag' => 'Ensorceleur',
            '' => 'Expert',
            '' => 'Fidèle défenseur',
            '' => 'Gardien de la nature',
            '' => 'Gardien du savoir',
            '' => 'Guerrier',
            '' => 'Héraut',
            '' => 'Homme d\'arme',
            '' => 'Homme du peuple',
            'Hyp' => 'Hypnotiseur',
            'Inq' => 'Inquisiteur',
            '' => 'Justicier',
            '' => 'Lutteur',
            '' => 'Magicien',
            'Mgs' => 'Magus',
            '' => 'Maître chymiste',
            '' => 'Maître des ombres',
            '' => 'Maître espion',
            'Méd' => 'Médium',
            '' => 'Métamorphe',
            '' => 'Moine',
            '' => 'Mystificateur profane',
            '' => 'Ninja',
            '' => 'Noble',
            'Occ' => 'Occultiste',
            '' => 'Oracle',
            'Pal' => 'Paladin',
            '' => 'Pistolier',
            'Prê/Ora' => 'Prêtre',
            'Prê' => 'Prêtre',
            '' => 'Prêtre combattant',
            '' => 'Prophète enragé',
            'Psy' => 'Psychiste',
            'Rôd' => 'Rôdeur',
            '' => 'Roublard',
            '' => 'Samouraï',
            'San' => 'Sanguin',
            '' => 'Scalde',
            'Sor' => 'Sorcière',
            'Spi' => 'Spirite',
            '' => 'Théurge mystique',
            '' => 'Tueur',
            '' => 'Vengeur sacré',
        ];

        return $names[$classShort] ?? null;
    }
}
