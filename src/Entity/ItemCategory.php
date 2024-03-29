<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Item\Repository\ItemCategoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ItemCategoryRepository::class)]
class ItemCategory implements Stringable
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    public int $id;
    #[ORM\Column(nullable: true)]
    public ?string $description = null;

    #[ORM\Column(type: Types::STRING, unique: true, nullable: true)]
    protected ?string $slug = null;

    #[ORM\ManyToOne(targetEntity: ItemCategory::class)]
    #[ORM\JoinColumn]
    public ?self $parent = null;

    /**
     * @var ItemCategory[]
     */
    public array $children = [];
    /**
     * @var Item[]
     */
    public array $armors = [];

    public function __construct(#[Assert\NotBlank]
        #[ORM\Column(nullable: false)]
        public ?string $name)
    {
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
