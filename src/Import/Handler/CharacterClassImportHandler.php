<?php


namespace AfmLibre\Pathfinder\Import\Handler;


use AfmLibre\Pathfinder\Entity\CharacterClass;
use AfmLibre\Pathfinder\Repository\CharacterClassRepository;

class CharacterClassImportHandler
{
    private CharacterClassRepository $characterClassRepository;

    public function __construct(CharacterClassRepository $characterClassRepository)
    {
        $this->characterClassRepository = $characterClassRepository;
    }

    public function call(array $classes)
    {
        foreach ($classes as $classData) {
            if (!$class = $this->characterClassRepository->findByName($classData['Nom'])) {
                $class = new CharacterClass();
                $class->setName($classData['Nom']);
                $die = preg_replace('/[^0-9]/', '', $classData['DésDeVie']);
                $class->setDieOfLive($die);
                $this->characterClassRepository->persist($class);

            }
        }
    }
}
