<?php


namespace AfmLibre\Pathfinder\Import\Handler;


use AfmLibre\Pathfinder\Entity\School;
use AfmLibre\Pathfinder\Entity\Spell;
use AfmLibre\Pathfinder\Entity\SpellClass;
use AfmLibre\Pathfinder\Level\LevelParser;
use AfmLibre\Pathfinder\Mapping\SpellYml;
use AfmLibre\Pathfinder\Repository\SchoolRepository;
use AfmLibre\Pathfinder\Repository\SpellClassRepository;
use AfmLibre\Pathfinder\Repository\SpellRepository;
use Symfony\Component\Console\Style\SymfonyStyle;

class SpellImportHandler
{
    public function __construct(private readonly SpellRepository $spellRepository, private readonly SpellClassRepository $spellClassRepository, private readonly SchoolRepository $schoolRepository, private readonly LevelParser $levelParser)
    {
    }

    public function call(SymfonyStyle $io, array $spells)
    {
        foreach ($spells as $spellData) {
            $spell = $this->createSpell($spellData);
            $this->spellRepository->persist($spell);
            try {
                $levels = $this->levelParser->parse($spellData['Niveau']);
                foreach ($levels as $levelDto) {
                    $spellClass = new SpellClass($spell, $levelDto->characterClass, $levelDto->level);
                    $this->spellClassRepository->persist($spellClass);
                }
            } catch (\Exception $e) {
                $io->error($e->getMessage().$spellData['Nom']);
            }
        }
        $this->spellRepository->flush();
        $this->spellClassRepository->flush();
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

    private function createSpell(array $data): Spell
    {
        $spell = new Spell();
        $spell->setName($data[SpellYml::YAML_NAME]);
        $spell->setDescription($data[SpellYml::YAML_DESC]);
        if (isset($data[SpellYml::YAML_DESCHTML])) {
            $spell->setDescriptionHtml($data[SpellYml::YAML_DESCHTML]);
        }
        if (isset($data[SpellYml::YAML_SCHOOL])) {
            $spell->setSchool($this->findSchool($data[SpellYml::YAML_SCHOOL]));
        }
        $spell->setReference($data[SpellYml::YAML_REFERENCE]);
        $spell->setSource($data[SpellYml::YAML_SOURCE]);

        if (isset($data[SpellYml::YAML_COMPONENTS])) {
            $spell->setComponents($data[SpellYml::YAML_COMPONENTS]);
        }
        if (isset($data[SpellYml::YAML_RANGE])) {
            $spell->setRange($data[SpellYml::YAML_RANGE]);
        }
        if (isset($data[SpellYml::YAML_TARGET])) {
            $spell->setTarget($data[SpellYml::YAML_TARGET]);
        }
        if (isset($data[SpellYml::YAML_DURATION])) {
            $spell->setDuration($data[SpellYml::YAML_DURATION]);
        }
        if (isset($data[SpellYml::YAML_SAVING])) {
            $spell->setSavingThrow($data[SpellYml::YAML_SAVING]);
        }
        if (isset($data[SpellYml::YAML_SPELL_RES])) {
            $spell->setSpellResistance($data[SpellYml::YAML_SPELL_RES]);
        }

        return $spell;
    }

    private function findSchool(?string $schoolName): School
    {
        if (!($school = $this->schoolRepository->findOneBy(['name' => $schoolName])) instanceof \AfmLibre\Pathfinder\Entity\School) {
            $school = new School($schoolName);
            $this->schoolRepository->persist($school);
            $this->schoolRepository->flush();
        }

        return $school;
    }

}
