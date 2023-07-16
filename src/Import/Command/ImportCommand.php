<?php

namespace AfmLibre\Pathfinder\Import\Command;

use AfmLibre\Pathfinder\Import\Handler\CharacterClassFeatureImportHandler;
use AfmLibre\Pathfinder\Import\Handler\CharacterClassImportHandler;
use AfmLibre\Pathfinder\Import\Handler\RaceImportHandler;
use AfmLibre\Pathfinder\Import\Handler\SpellImportHandler;
use AfmLibre\Pathfinder\Repository\CharacterClassRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Yaml\Yaml;

/**
 * source https://github.com/SvenWerlen/pathfinderfr-data/
 */
class ImportCommand extends Command
{
    protected static $defaultName = 'pathfinder:import';

    public function __construct(
        private readonly CharacterClassRepository $characterClassRepository,
        private readonly CharacterClassImportHandler $characterClassImportHandler,
        private readonly SpellImportHandler $spellImportHandler,
        private readonly RaceImportHandler $raceImportHandler,
        private readonly CharacterClassFeatureImportHandler $characterClassFeatureImportHandler,
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
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
            default => $io->error('Valeurs possibles: classes classfeatures spells races'),
        };

        return Command::SUCCESS;
    }

    private function readFile(string $fileName): array
    {
        return Yaml::parseFile(__DIR__.'/../../../../../../data/'.$fileName.'.yml');
    }
}
