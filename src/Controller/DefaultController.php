<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Entity\Spell;
use AfmLibre\Pathfinder\Mapping\SpellYml;
use AfmLibre\Pathfinder\Repository\CharacterClassRepository;
use AfmLibre\Pathfinder\Repository\SpellRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Yaml\Yaml;

class DefaultController extends AbstractController
{
    private $spellRepository;
    private $characterClassRepository;

    public function __construct(SpellRepository $spellRepository, CharacterClassRepository $characterClassRepository)
    {
        $this->spellRepository = $spellRepository;
        $this->characterClassRepository = $characterClassRepository;
    }

    /**
     * @Route("/", name="pathfinder_home")
     */
    public function index()
    {
        $spells = Yaml::parseFile(__DIR__.'/../../data/spells.yml');
        $sorts = [];
        $i = 0;

        foreach ($spells as $data) {
            //  dump($data);
            $spell = new Spell();
            $spell->setName($data[SpellYml::YAML_NAME]);
            $spell->setDescription($data[SpellYml::YAML_DESC]);
            $spell->setSchool($data[SpellYml::YAML_SCHOOL]);
            $spell->setReference($data[SpellYml::YAML_REFERENCE]);
            $spell->setSource($data[SpellYml::YAML_SOURCE]);
            $spell->setLevel($data[SpellYml::YAML_LEVEL]);

            $sorts[$i]["nom"] = $data[SpellYml::YAML_NAME];
            $sorts[$i]["levels"] = $levels = $this->getLevel($data[SpellYml::YAML_LEVEL]);

            foreach ($levels as $level) {
                $characterClass = $this->getCharacterClass($level['class']);
                $spell->addCharacterClass($characterClass);
            }

            if (isset($data[SpellYml::YAML_CASTING])) {
                $spell->setCastingTime($data[SpellYml::YAML_CASTING]);
            }
            if (isset($data[SpellYml::YAML_COMPONENTS])) {
                $spell->setComponents($data[SpellYml::YAML_COMPONENTS]);
            }
            if (isset($data[SpellYml::YAML_RANGE])) {
                $spell->setRange($data[SpellYml::YAML_RANGE]);
            }
            if (isset($data[SpellYml::YAML_TARGET])) {
                $spell->setTarget($data[SpellYml::YAML_TARGET]);
            }
            $spell->setDuration($data[SpellYml::YAML_DURATION]);
            if (isset($data[SpellYml::YAML_SAVING])) {
                $spell->setSavingThrow($data[SpellYml::YAML_SAVING]);
            }
            if (isset($data[SpellYml::YAML_SPELL_RES])) {
                $spell->setSpellResistance($data[SpellYml::YAML_SPELL_RES]);
            }
            $i++;
            //    $this->spellRepository->persist($spell);
        }

        //  $this->spellRepository->flush();

        return $this->render(
            'default/index.html.twig',
            [
                'spells' => $sorts,
            ]
        );
    }

    private function getLevel(string $level)
    {
        $result = [];
        $i = 0;
        if (preg_match("#,#", $level)) {
            $data = explode(",", $level);
            //   dump($data);
            foreach ($data as $values) {
                $number = (int)filter_var($values, FILTER_SANITIZE_NUMBER_INT);
                $class = trim(preg_replace("/$number/", '', $values));
                //   dump($class);
                $result[$i]['class'] = $class;
                $result[$i]['level'] = $number;
                $i++;
            }
        }

        return $result;
    }

    private function words()
    {
        $pretres = ['prêtre', 'Prê'];
    }

    private function getCharacterClass(string $class)
    {
        if (in_array($this->words(), $class)) {
            $this->getPretre();
        }
    }

    private function getPretre()
    {
        $this->characterClassRepository->findBy(['name' => 'Prêtre']);
    }
}
