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
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * sourced https://github.com/SvenWerlen/pathfinderfr-data/
 */
#[AsCommand(
    name: 'pathfinder:import',
    description: 'Add a short description for your command',
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

    protected function configure()
    {
        $this
            ->addArgument('name', InputArgument::OPTIONAL, 'classes, spells');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
        $argument = $input->getArgument('name');

        match ($argument) {
            'classes' => $this->classTImportHandler->call($this->io, $this->readFile($argument)),
            'classfeatures' => $this->classTFeatureImportHandler->call($this->io, $this->readFile($argument)),
            'spells' => $this->spellImportHandler->call($this->io, $this->readFile($argument)),
            'races' => $this->raceImportHandler->call($this->io, $this->readFile($argument)),
            'skills' => $this->skillImportHandler->call($this->io, $this->readFile($argument)),
            'feats' => $this->featImportHandler->call($this->io, $this->readFile($argument)),
            'armors' => $this->armorImportHandler->call($this->io, $this->readFile($argument)),
            'weapons' => $this->weaponImportHandler->call($this->io, $this->readFile($argument)),
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
        $this->classTImportHandler->call($this->io, $this->readFile('classes'));
        $this->classTFeatureImportHandler->call($this->io, $this->readFile('classfeatures'));
        $this->spellImportHandler->call($this->io, $this->readFile('spells'));
        $this->raceImportHandler->call($this->io, $this->readFile('races'));
        $this->skillImportHandler->call($this->io, $this->readFile('skills'));
        $this->featImportHandler->call($this->io, $this->readFile('feats'));
        $this->armorImportHandler->call($this->io, $this->readFile('armors'));
        $this->weaponImportHandler->call($this->io, $this->readFile('weapons'));
    }
}
