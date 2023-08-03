<?php


namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Entity\Traits\NameTrait;
use AfmLibre\Pathfinder\Entity\Traits\SlugTrait;
use AfmLibre\Pathfinder\Entity\Traits\UuidTrait;
use AfmLibre\Pathfinder\Modifier\ModifierCalculator;
use AfmLibre\Pathfinder\Repository\CharacterRepository;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\SluggableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Sluggable\SluggableTrait;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;

#[ORM\Table(name: 'characters')]//!character reserved
#[ORM\Entity(repositoryClass: CharacterRepository::class)]
class Character implements SluggableInterface, TimestampableInterface, \Stringable
{
    use IdTrait;
    use NameTrait;
    use UuidTrait;
    use SluggableTrait;
    use TimestampableTrait;
    use SlugTrait;

    #[ORM\Column(type: 'text', nullable: true)]
    public ?string $description = null;

    #[ORM\Column(type: 'smallint', nullable: false)]
    public int $strength = 10;
    #[ORM\Column(type: 'smallint', nullable: false)]
    public int $dexterity = 10;
    #[ORM\Column(type: 'smallint', nullable: false)]
    public int $constitution = 10;
    #[ORM\Column(type: 'smallint', nullable: false)]
    public int $intelligence = 10;
    #[ORM\Column(type: 'smallint', nullable: false)]
    public int $wisdom = 10;
    #[ORM\Column(type: 'smallint', nullable: false)]
    public int $charisma = 10;

    #[ORM\Column(nullable: true)]
    public ?string $point_by_level = null;

    #[ORM\ManyToOne(targetEntity: Race::class)]
    #[ORM\JoinColumn(nullable: false)]
    public ?Race $race = null;

    #[ORM\ManyToOne(targetEntity: ClassT::class)]
    #[ORM\JoinColumn(nullable: false)]
    public ?ClassT $classT = null;

    #[ORM\ManyToOne(targetEntity: Level::class)]
    #[ORM\JoinColumn(nullable: false)]
    public ?Level $current_level = null;

    public int $select_level;

    /**
     * @var Armor[]
     */
    public array $armors = [];

    /**
     * @var Weapon[]
     */
    public array $weapons = [];

    public function __construct()
    {

    }

    public function __toString(): string
    {
        return (string)$this->name;
    }

    public static function getValueModifier(int $value): int
    {
        return ModifierCalculator::abilityValueModifier($value);
    }

    public function shouldGenerateUniqueSlugs(): bool
    {
        return true;
    }

    public function addSpell(CharacterSpell $spell): self
    {
        if (!$this->character_spells_selection->contains($spell)) {
            $this->character_spells_selection[] = $spell;
            $spell->character = $this;
        }

        return $this;
    }

    public function removeSpell(CharacterSpell $spell): self
    {
        // set the owning side to null (unless already changed)
        if ($this->character_spells_selection->removeElement($spell) && $spell->character === $this) {
            $spell->character = null;
        }

        return $this;
    }

    public function addCharacterSpellsSelection(CharacterSpell $characterSpellsSelection): self
    {
        if (!$this->character_spells_selection->contains($characterSpellsSelection)) {
            $this->character_spells_selection[] = $characterSpellsSelection;
            $characterSpellsSelection->character = $this;
        }

        return $this;
    }

    public function removeCharacterSpellsSelection(CharacterSpell $characterSpellsSelection): self
    {
        // set the owning side to null (unless already changed)
        if ($this->character_spells_selection->removeElement(
                $characterSpellsSelection
            ) && $characterSpellsSelection->character === $this) {
            $characterSpellsSelection->character = null;
        }

        return $this;
    }

    public function addCharacterSpellProfile(SpellProfile $characterSpellProfile): self
    {
        if (!$this->character_spell_profiles->contains($characterSpellProfile)) {
            $this->character_spell_profiles[] = $characterSpellProfile;
            $characterSpellProfile->character = $this;
        }

        return $this;
    }

    public function removeCharacterSpellProfile(SpellProfile $characterSpellProfile): self
    {
        // set the owning side to null (unless already changed)
        if ($this->character_spell_profiles->removeElement(
                $characterSpellProfile
            ) && $characterSpellProfile->character === $this) {
            $characterSpellProfile->character = null;
        }

        return $this;
    }
}
