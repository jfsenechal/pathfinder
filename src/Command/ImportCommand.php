<?php

namespace AfmLibre\Pathfinder\Command;

use AfmLibre\Pathfinder\Import\Handler\ArmorImportHandler;
use AfmLibre\Pathfinder\Import\Handler\CharacterClassFeatureImportHandler;
use AfmLibre\Pathfinder\Import\Handler\ClassImportHandler;
use AfmLibre\Pathfinder\Import\Handler\FeatImportHandler;
use AfmLibre\Pathfinder\Import\Handler\RaceImportHandler;
use AfmLibre\Pathfinder\Import\Handler\SkillImportHandler;
use AfmLibre\Pathfinder\Import\Handler\SpellImportHandler;
use AfmLibre\Pathfinder\Import\Handler\WeaponImportHandler;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * sourced https://github.com/SvenWerlen/pathfinderfr-data/
 */
#[AsCommand(
    name: 'pathfinder:import',
    description: 'Import data from yaml files',
)]
class ImportCommand extends Command
{
    private SymfonyStyle $io;

    public function __construct(
        private readonly ClassImportHandler $classTImportHandler,
        private readonly SpellImportHandler $spellImportHandler,
        private readonly RaceImportHandler $raceImportHandler,
        private readonly SkillImportHandler $skillImportHandler,
        private readonly FeatImportHandler $featImportHandler,
        private readonly CharacterClassFeatureImportHandler $classTFeatureImportHandler,
        private readonly ArmorImportHandler $armorImportHandler,
        private readonly WeaponImportHandler $weaponImportHandler,
        private readonly ParameterBagInterface $parameterBag
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::OPTIONAL, 'What kind of data');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
        $name = $input->getArgument('name');
        $kinds = ['all', 'skills', 'classes', 'classfeatures', 'spells', 'races', 'feats', 'armors', 'weapons'];

        if (!$name) {
            $helper = $this->getHelper('question');
            $question = new ChoiceQuestion(
                'What do you want to import ?',
                $kinds
            );

            $question->setErrorMessage('This choice %s is invalid.');

            $name = $helper->ask($input, $output, $question);
        }

        $output->writeln($name.' import...');

        match ($name) {
            'skills' => $this->skillImportHandler->call($this->io, $this->readFile($name)),
            'classes' => $this->classTImportHandler->call($this->io, $this->readFile($name)),
            'classfeatures' => $this->classTFeatureImportHandler->call($this->io, $this->readFile($name)),
            'spells' => $this->spellImportHandler->call($this->io, $this->readFile($name)),
            'races' => $this->raceImportHandler->call($this->io, $this->readFile($name)),
            'feats' => $this->featImportHandler->call($this->io, $this->readFile($name)),
            'armors' => $this->armorImportHandler->call($this->io, $this->readFile($name)),
            'weapons' => $this->weaponImportHandler->call($this->io, $this->readFile($name)),
            'all' => $this->importAll(),
            default => $this->io->error('Valeurs possibles: classes classfeatures spells races'),
        };

        return Command::SUCCESS;
    }

    private function readFile(string $fileName): array
    {
        return Yaml::parseFile($this->parameterBag->get('kernel.project_dir').'/data/'.$fileName.'.yml');
    }

    private function importAll()
    {
        $this->skillImportHandler->call($this->io, $this->readFile('skills'));
        $this->raceImportHandler->call($this->io, $this->readFile('races'));
        $this->featImportHandler->call($this->io, $this->readFile('feats'));
        $this->armorImportHandler->call($this->io, $this->readFile('armors'));
        $this->weaponImportHandler->call($this->io, $this->readFile('weapons'));
        $this->classTImportHandler->call($this->io, $this->readFile('classes'));
        $this->classTFeatureImportHandler->call($this->io, $this->readFile('classfeatures'));
        $this->spellImportHandler->call($this->io, $this->readFile('spells'));
    }
}
