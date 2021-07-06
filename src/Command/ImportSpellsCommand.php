<?php

namespace AfmLibre\Pathfinder\Command;

use AfmLibre\Pathfinder\Entity\CharacterClass;
use AfmLibre\Pathfinder\Entity\Spell;
use AfmLibre\Pathfinder\Entity\SpellClassLevel;
use AfmLibre\Pathfinder\Level\LevelParser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Yaml\Yaml;

/**
 * Class ImportSpellsCommand
 * @package App\Command
 * https://github.com/SvenWerlen/pathfinderfr-data/tree/master/data
 */
class ImportSpellsCommand extends Command
{
    protected static $defaultName = 'pathfinder:import-spells';

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $spells = Yaml::parseFile(__DIR__.'/../../data/spells.yml');

        foreach ($spells as $spellData) {
            $spell = new Spell();
            $spell->setName($spellData['Nom']);
            $levels = LevelParser::parse($spellData['Niveau']);
            foreach ($levels as $levelDto) {
                $spellClass = new SpellClassLevel($spell, $levelDto->characterClass, $levelDto->level);
            }

            dd($levels);
            break;
        }
    }

    /**
     * "Nom" => "Abondance de munitions"
     * "École" => "Invocation (convocation) ;"
     * "Niveau" => "Bar 1, Ens/Mag 1, Prê/Ora 1, Psy 1, Rôd 1"
     * "Cible ou zone d'effet" => "récipient touché"
     * "Temps d'incantation" => "1 action simple"
     * "Composantes" => "V, G, M/FD (une munition)"
     * "Durée" => "1 minute/niveau"
     * "Jet de sauvegarde" => "aucun ;"
     * "Résistance à la magie" => "non"
     * "Description" => "Quand on lance ce sort sur un récipient comme un carquois ou une bourse qui contient des munitions non magiques ou des shuriken (éventuellement de maître, mais pas en matériau spécial, avec des propriétés alchimiques, ou des modifications non-magiques), au début de chaque round, il remplace les munitions utilisées au round précédent. Les munitions sorties du récipient au round précédent disparaissent. Si le personnage lance un sort qui améliore les projectiles (comme arme alignée ou arme magique suprême) sur le récipient après avoir lancé ce sort, tous les projectiles qu’invoque le premier sort sont affectés par le second."
     * "DescriptionHTML" => "Quand on lance ce sort sur un récipient comme un carquois ou une bourse qui contient des munitions non magiques ou des shuriken (éventuellement de maître, mais pas en matériau spécial, avec des propriétés alchimiques, ou des modifications non-magiques), au début de chaque round, il remplace les munitions utilisées au round précédent. Les munitions sorties du récipient au round précédent disparaissent. Si le personnage lance un sort qui améliore les projectiles (comme arme alignée ou arme magique suprême) sur le récipient après avoir lancé ce sort, tous les projectiles qu’invoque le premier sort sont affectés par le second. \r \t"
     * "Source" => "AG"
     * "Référence" => "http://www.pathfinder-fr.org/wiki/pathfinder-rpg.abondance%20de%20munitions.ashx"
     */
}
