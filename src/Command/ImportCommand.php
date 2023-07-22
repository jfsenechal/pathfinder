<?php

namespace AfmLibre\Pathfinder\Command;

use AfmLibre\Pathfinder\Import\Handler\CharacterClassFeatureImportHandler;
use AfmLibre\Pathfinder\Import\Handler\CharacterClassImportHandler;
use AfmLibre\Pathfinder\Import\Handler\RaceImportHandler;
use AfmLibre\Pathfinder\Import\Handler\SkillImportHandler;
use AfmLibre\Pathfinder\Import\Handler\SpellImportHandler;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * source https://github.com/SvenWerlen/pathfinderfr-data/
 */
#[AsCommand(
    name: 'pathfinder:import',
    description: 'Add a short description for your command',
)]
class ImportCommand extends Command
{
    public function __construct(
        private readonly CharacterClassImportHandler $characterClassImportHandler,
        private readonly SpellImportHandler $spellImportHandler,
        private readonly RaceImportHandler $raceImportHandler,
        private readonly SkillImportHandler $skillImportHandler,
        private readonly CharacterClassFeatureImportHandler $characterClassFeatureImportHandler,
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
        $io = new SymfonyStyle($input, $output);
        $argument = $input->getArgument('name');

        match ($argument) {
            'classes' => $this->characterClassImportHandler->call($io, $this->readFile($argument)),
            'classfeatures' => $this->characterClassFeatureImportHandler->call($io, $this->readFile($argument)),
            'spells' => $this->spellImportHandler->call($io, $this->readFile($argument)),
            'races' => $this->raceImportHandler->call($io, $this->readFile($argument)),
            'skills' => $this->skillImportHandler->call($io, $this->readFile($argument)),
            default => $io->error('Valeurs possibles: classes classfeatures spells races'),
        };

        return Command::SUCCESS;
    }

    private function readFile(string $fileName): array
    {
        return Yaml::parseFile($this->parameterBag->get('kernel.project_dir').'/data/'.$fileName.'.yml');
    }
}
