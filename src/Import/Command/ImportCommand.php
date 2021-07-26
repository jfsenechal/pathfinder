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

    private CharacterClassRepository $characterClassRepository;
    private CharacterClassImportHandler $characterClassImportHandler;
    private SpellImportHandler $spellImportHandler;
    private RaceImportHandler $raceImportHandler;
    private CharacterClassFeatureImportHandler $characterClassFeatureImportHandler;

    public function __construct(
        string $name = null,
        CharacterClassRepository $characterClassRepository,
        CharacterClassImportHandler $characterClassImportHandler,
        SpellImportHandler $spellImportHandler,
        RaceImportHandler $raceImportHandler,
        CharacterClassFeatureImportHandler $characterClassFeatureImportHandler
    ) {
        parent::__construct($name);
        $this->characterClassRepository = $characterClassRepository;
        $this->characterClassImportHandler = $characterClassImportHandler;
        $this->spellImportHandler = $spellImportHandler;
        $this->raceImportHandler = $raceImportHandler;
        $this->characterClassFeatureImportHandler = $characterClassFeatureImportHandler;
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

        switch ($argument) {
            case 'classes':
                $this->characterClassImportHandler->call($io, $this->readFile($argument));
                break;
            case 'classfeatures':
                $this->characterClassFeatureImportHandler->call($io, $this->readFile($argument));
                break;
            case 'spells':
                $this->spellImportHandler->call($io, $this->readFile($argument));
                break;
            case 'races':
                $this->raceImportHandler->call($io, $this->readFile($argument));
                break;
            default:
                $io->error('Valeurs possibles: classes classfeatures spells races');
        }

        return Command::SUCCESS;
    }

    private function readFile(string $fileName): array
    {
        return Yaml::parseFile(__DIR__.'/../../../../../../data/'.$fileName.'.yml');
    }
}
