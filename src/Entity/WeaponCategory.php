<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Weapon\Repository\WeaponCategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: WeaponCategoryRepository::class)]
class WeaponCategory implements Stringable
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    public int $id;
    #[Assert\NotBlank]
    #[ORM\Column(nullable: false)]
    public ?string $name = null;
    #[ORM\Column(type: 'string', nullable: true)]
    public ?string $description = null;

    #[ORM\Column(type: 'string', unique: true, nullable: true)]
    protected $slug = null;

    #[ORM\ManyToOne(targetEntity: WeaponCategory::class)]
    #[ORM\JoinColumn(nullable: true)]
    public ?self $parent = null;

    /**
     * @var ArmorCategory[]
     */
    public array $children = [];

    /**
     * @var Weapon[]
     */
    public array $weapons;

    public function __toString(): string
    {
        return (string)$this->name;
    }

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getSluggableFields(): array
    {
        return ['name', 'id'];
    }

    public function shouldGenerateUniqueSlugs(): bool
    {
        return true;
    }
}
