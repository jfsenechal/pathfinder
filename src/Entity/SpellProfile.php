<?php


namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\CharacterSpellsTrait;
use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Entity\Traits\NameTrait;
use AfmLibre\Pathfinder\Entity\Traits\SlugTrait;
use AfmLibre\Pathfinder\Entity\Traits\UuidTrait;
use AfmLibre\Pathfinder\Repository\SpellProfileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\SluggableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Sluggable\SluggableTrait;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;

#[ORM\Entity(repositoryClass: SpellProfileRepository::class)]
//#[ORM\UniqueConstraint(columns: ['character_id', 'spell_id'])]
class SpellProfile implements SluggableInterface, TimestampableInterface, \Stringable
{
    use IdTrait;
    use NameTrait;
    use UuidTrait;
    use SluggableTrait;
    use TimestampableTrait;
    use SlugTrait;
    use CharacterSpellsTrait;

    #[ORM\Column(type: 'text', nullable: true)]
    public ?string $description = null;

    /**
     * @var SpellProfileCharacter[]
     */
    #[ORM\OneToMany(targetEntity: SpellProfileCharacter::class, mappedBy: 'spell_profile')]
    public iterable $spells_profile_character;

    #[ORM\ManyToOne(targetEntity: Character::class, inversedBy: 'character_spell_profiles')]
    #[ORM\JoinColumn(name: 'character_id', nullable: false)]
    public ?Character $character;

    public function __construct(Character $character)
    {
        $this->character = $character;
        $this->spells_profile_character = new ArrayCollection();
        $this->character_spells = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string)$this->name;
    }

    public function shouldGenerateUniqueSlugs(): bool
    {
        return true;
    }

    public function addSpellProfileCharacter(SpellProfileCharacter $spellProfileCharacter): self
    {
        if (!$this->spells_profile_character->contains($spellProfileCharacter)) {
            $this->spells_profile_character[] = $spellProfileCharacter;
            $spellProfileCharacter->spell_profile = $this;
        }

        return $this;
    }

    public function removeSpellProfileCharacter(SpellProfileCharacter $spellProfileCharacter): self
    {
        // set the owning side to null (unless already changed)
        if ($this->spells_profile_character->removeElement(
            $spellProfileCharacter
        ) && $spellProfileCharacter->spell_profile === $this) {
            $spellProfileCharacter->spell_profile = null;
        }

        return $this;
    }
}
