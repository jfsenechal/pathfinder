<?php

namespace AfmLibre\Pathfinder\Import\Handler;

use AfmLibre\Pathfinder\Entity\Skill;
use AfmLibre\Pathfinder\Skill\Repository\SkillRepository;
use Symfony\Component\Console\Style\SymfonyStyle;

class SkillImportHandler
{
    public function __construct(
        private readonly SkillRepository $skillRepository,
    ) {
    }

    public function call(SymfonyStyle $io, array $skills)
    {
        $io->section('SKILLS');

        foreach ($skills as $skillData) {
            $io->writeln($skillData['Nom']);
            $race = $this->createSkill($skillData);
            $this->skillRepository->persist($race);
        }
        $this->skillRepository->flush();
    }

    private function createSkill(array $data): Skill
    {
        if (!$skill = $this->skillRepository->findOneByName($data['Nom'])) {
            $skill = new Skill();
        }
        $skill->name = $data['Nom'];
        $skill->ability = $data['Caractéristique associée'];
        $skill->training = $data['Formation nécessaire'] == 'oui' ? true : false;
        $skill->descriptoin = $data['Description'];
        $skill->descriptionHtml = $data['DescriptionHTML'];
        $skill->sourced = $data['Référence'];

        return $skill;
    }
}
