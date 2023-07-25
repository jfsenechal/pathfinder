<?php


namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\CharacterSpellsTrait;
use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Entity\Traits\NameTrait;
use AfmLibre\Pathfinder\Entity\Traits\SlugTrait;
use AfmLibre\Pathfinder\Entity\Traits\UuidTrait;
use AfmLibre\Pathfinder\Repository\SpellProfileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @var SpellProfileCharacterSpell[]
     */
    #[ORM\OneToMany(targetEntity: SpellProfileCharacterSpell::class, mappedBy: 'spell_profile')]
    public iterable $spell_profile_character_spells;

    #[ORM\ManyToOne(targetEntity: Character::class, inversedBy: 'character_spell_profiles')]
    #[ORM\JoinColumn(name: 'character_id', nullable: false)]
    public ?Character $character;

    public function __construct(Character $character)
    {
        $this->character = $character;
        $this->spell_profile_character_spells = new ArrayCollection();
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

    public function addSpellProfileCharacterSpell(SpellProfileCharacterSpell $spellProfileCharacterSpell): self
    {
        if (!$this->spell_profile_character_spells->contains($spellProfileCharacterSpell)) {
            $this->spell_profile_character_spells[] = $spellProfileCharacterSpell;
            $spellProfileCharacterSpell->setSpellProfile($this);
        }

        return $this;
    }

    public function removeSpellProfileCharacterSpell(SpellProfileCharacterSpell $spellProfileCharacterSpell): self
    {
        // set the owning side to null (unless already changed)
        if ($this->spell_profile_character_spells->removeElement(
                $spellProfileCharacterSpell
            ) && $spellProfileCharacterSpell->getSpellProfile() === $this) {
            $spellProfileCharacterSpell->setSpellProfile(null);
        }

        return $this;
    }


}
