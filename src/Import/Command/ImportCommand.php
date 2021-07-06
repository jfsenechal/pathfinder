<?php

namespace AfmLibre\Pathfinder\Import\Command;

use AfmLibre\Pathfinder\Entity\CharacterClass;
use AfmLibre\Pathfinder\Repository\CharacterClassRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Yaml\Yaml;

class ImportCommand extends Command
{
    protected static $defaultName = 'app:import';
    private CharacterClassRepository $characterClassRepository;

    public function __construct(string $name = null, CharacterClassRepository $characterClassRepository)
    {
        parent::__construct($name);
        $this->characterClassRepository = $characterClassRepository;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('name', InputArgument::REQUIRED, 'Argument description');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $argument = $input->getArgument('name');

        $classes = Yaml::parseFile(__DIR__.'/../../../data/'.$argument.'.yml');

        foreach ($classes as $classData) {

            if (!$class = $this->characterClassRepository->findByName($classData['Nom'])) {
                $class = new CharacterClass();
                $class->setName($classData['Nom']);
                $die = preg_replace('/[^0-9]/', '', $classData['DésDeVie']);
                $class->setDieOfLive($die);
                $this->characterClassRepository->persist($class);

            }
            $io->writeln($classData['Nom']);
        }

        $this->characterClassRepository->flush();

        return Command::SUCCESS;
    }
}
