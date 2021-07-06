<?php


namespace AfmLibre\Pathfinder\Level;


use AfmLibre\Pathfinder\Repository\CharacterClassRepository;

class LevelParser
{
    private CharacterClassRepository $characterClassRepository;

    public function __construct(CharacterClassRepository $characterClassRepository)
    {
        $this->characterClassRepository = $characterClassRepository;
    }

    /**
     * "Niveau" => "Bar 1, Ens/Mag 1, Prê/Ora 1, Psy 1, Rôd 1"
     * @param string $text
     * @return \App\Level\LevelDto[]
     */
    public  function parse(string $text): array
    {
        $levels = [];
        $data = explode(', ', $text);
        foreach ($data as $level) {
            list($name, $level) = explode(' ', $level);
            $character = $this->characterClassRepository->findByShortName($name);
            $levels[] =             new LevelDto($character, $level);
        }

        return $levels;
    }
}
