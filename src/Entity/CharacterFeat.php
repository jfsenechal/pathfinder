<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Character\Repository\CharacterFeatRepository;
use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\UniqueConstraint(columns: ['character_id', 'feat_id'])]
#[ORM\Entity(repositoryClass: CharacterFeatRepository::class)]
#[UniqueEntity(fields: ['character', 'feat'], message: 'Don déjà acquis')]
class CharacterFeat implements \Stringable
{
    use IdTrait;

    #[ORM\ManyToOne(targetEntity: Character::class)]
    #[ORM\JoinColumn(name: 'character_id', nullable: false)]
    public ?Character $character;

    #[ORM\ManyToOne(targetEntity: Feat::class)]
    #[ORM\JoinColumn(nullable: false)]
    public ?Feat $feat;

    #[ORM\Column(nullable: true)]
    public ?string $emplacement;

    public function __construct(Character $character, Feat $feat)
    {
        $this->character = $character;
        $this->feat = $feat;
    }

    public function __toString(): string
    {
        return (string)$this->feat->name;
    }
}
