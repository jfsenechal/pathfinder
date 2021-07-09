<?php

namespace AfmLibre\Pathfinder\Import\Command;

use AfmLibre\Pathfinder\Entity\CharacterClass;
use AfmLibre\Pathfinder\Import\Handler\CharacterClassImportHandler;
use AfmLibre\Pathfinder\Import\Handler\SpellImportHandler;
use AfmLibre\Pathfinder\Repository\CharacterClassRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Yaml\Yaml;

class ImportCommand extends Command
{
    protected static $defaultName = 'pathfinder:import';
    private CharacterClassRepository $characterClassRepository;
    private CharacterClassImportHandler $characterClassImportHandler;
    private SpellImportHandler $spellImportHandler;

    public function __construct(
        string $name = null,
        CharacterClassRepository $characterClassRepository,
        CharacterClassImportHandler $characterClassImportHandler,
        SpellImportHandler $spellImportHandler
    ) {
        parent::__construct($name);
        $this->characterClassRepository = $characterClassRepository;
        $this->characterClassImportHandler = $characterClassImportHandler;
        $this->spellImportHandler = $spellImportHandler;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('name', InputArgument::REQUIRED, 'classes, spells');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $argument = $input->getArgument('name');

        switch ($argument) {
            case 'classes':
                $classes = Yaml::parseFile(__DIR__.'/../../../../../../data/'.$argument.'.yml');
                $this->characterClassImportHandler->call($classes);
                break;
            case 'spells':
                $spells = Yaml::parseFile(__DIR__.'/../../../../../../data/'.$argument.'.yml');
                $this->spellImportHandler->call($io, $spells);
                break;
        }


        //   $this->characterClassRepository->flush();

        return Command::SUCCESS;
    }
}
