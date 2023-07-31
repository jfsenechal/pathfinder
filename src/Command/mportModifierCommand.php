<?php

namespace AfmLibre\Pathfinder\Command;

use AfmLibre\Pathfinder\Entity\Modifier;
use AfmLibre\Pathfinder\Entity\Race;
use AfmLibre\Pathfinder\Modifier\ModifierEnum;
use AfmLibre\Pathfinder\Repository\ModifierRepository;
use AfmLibre\Pathfinder\Repository\RaceRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'pathfinder:import-modifier',
    description: 'Add a short description for your command',
)]
class mportModifierCommand extends Command
{
    public function __construct(private RaceRepository $raceRepository, private ModifierRepository $modifierRepository)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $className = Race::class;
        $races = [
            'Elfe' => [
                ModifierEnum::ABILITY_DEXTERITY->value => +2,
                ModifierEnum::ABILITY_INTELLIGENCE->value => +2,
                ModifierEnum::ABILITY_CONSTITUTION->value => -2,
            ],
        ];
        foreach ($races as $name => $values) {
            $race = $this->raceRepository->findByName($name);
            foreach ($values as $ability => $value) {
                $modifier = new Modifier($race->getId(), $className);
                $modifier->value = $value;
                $modifier->ability = ModifierEnum::findByName($ability);
                $this->modifierRepository->persist($modifier);
            }
        }

        $this->modifierRepository->flush();

        return Command::SUCCESS;
    }
}
