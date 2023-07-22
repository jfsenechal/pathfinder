<?php

namespace AfmLibre\Pathfinder\Import\Handler;

use AfmLibre\Pathfinder\Entity\Skill;
use AfmLibre\Pathfinder\Repository\SkillRepository;
use Symfony\Component\Console\Style\SymfonyStyle;

class SkillImportHandler
{
    public function __construct(
        private readonly SkillRepository $skillRepository,
    ) {
    }

    public function call(SymfonyStyle $io, array $skills)
    {
        foreach ($skills as $skillData) {
            $io->writeln($skillData['Nom']);
            $race = $this->createSkill($skillData);
            $this->skillRepository->persist($race);
        }
        $this->skillRepository->flush();
    }

    private function createSkill(array $data): Skill
    {
        $skill = new Skill();
        $skill->setName($data['Nom']);
        $skill->ability = $data['Caractéristique associée'];
        $skill->training = $data['Formation nécessaire'] == 'oui' ? true : false;
        $skill->descriptoin = $data['Description'];
        $skill->descriptionHtml = $data['DescriptionHTML'];
        $skill->source = $data['Référence'];

        return $skill;
    }

}
