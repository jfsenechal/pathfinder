<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Item\Repository\ItemCategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ItemCategoryRepository::class)]
class ItemCategory implements Stringable
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    public int $id;
    #[Assert\NotBlank]
    #[ORM\Column(nullable: false)]
    public ?string $name = null;
    #[ORM\Column(nullable: true)]
    public ?string $description = null;

    #[ORM\Column(type: 'string', unique: true, nullable: true)]
    protected $slug = null;

    #[ORM\ManyToOne(targetEntity: ItemCategory::class)]
    #[ORM\JoinColumn(nullable: true)]
    public ?self $parent = null;

    /**
     * @var ItemCategory[]
     */
    public array $children = [];
    /**
     * @var Item[]
     */
    public array $armors = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function __toString(): string
    {
        return (string)$this->name;
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
