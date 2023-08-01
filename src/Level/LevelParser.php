<?php


namespace AfmLibre\Pathfinder\Level;

use AfmLibre\Pathfinder\Classes\ClassParser;
use AfmLibre\Pathfinder\Repository\ClassRepository;

class LevelParser
{
    public function __construct(private readonly ClassRepository $classTRepository)
    {
    }

    /**
     * "Niveau" => "Bar 1, Ens/Mag 1, Prê/Ora 1, Psy 1, Rôd 1"
     * @return LevelDto[]
     * @throws \Exception
     */
    public function parse(string $text): array
    {
        $levels = [];
        if ($text == '') {
            throw new \Exception('level not found ' . $text);
        }
        $data = explode(', ', $text);
        foreach ($data as $level) {
            [$shortName, $level] = explode(' ', $level);
            $name = ClassParser::getClassName($shortName);
            $character = $this->classTRepository->findOneByName($name);
            // dump($character->getName());
            $levels[] = new LevelDto($character, $level);
        }

        return $levels;
    }
}
