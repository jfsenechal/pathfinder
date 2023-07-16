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
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * ORM\Table(uniqueConstraints={
 *     ORM\UniqueConstraint(columns={"character_id", "spell_id"})
 * })
 */
#[ORM\Entity(repositoryClass: SpellProfileRepository::class)]
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
    protected ?string $description = null;

    /**
     * @var SpellProfileCharacterSpell[]
     */
    #[ORM\OneToMany(targetEntity: SpellProfileCharacterSpell::class, mappedBy: 'spell_profile')]
    private iterable $spell_profile_character_spells;

    public function __construct(#[ORM\ManyToOne(targetEntity: Character::class, inversedBy: 'character_spell_profiles')]
    #[ORM\JoinColumn(name: 'character_id', nullable: false)]
    private ?Character $character_player)
    {
        $this->spell_profile_character_spells = new ArrayCollection();
        $this->character_spells = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string) $this->name;
    }

    public function shouldGenerateUniqueSlugs(): bool
    {
        return true;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCharacterPlayer(): ?Character
    {
        return $this->character_player;
    }

    public function setCharacterPlayer(?Character $character_player): self
    {
        $this->character_player = $character_player;

        return $this;
    }

    /**
     * @return Collection|SpellProfileCharacterSpell[]
     */
    public function getSpellProfileCharacterSpells(): Collection
    {
        return $this->spell_profile_character_spells;
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
        if ($this->spell_profile_character_spells->removeElement($spellProfileCharacterSpell)) {
            // set the owning side to null (unless already changed)
            if ($spellProfileCharacterSpell->getSpellProfile() === $this) {
                $spellProfileCharacterSpell->setSpellProfile(null);
            }
        }

        return $this;
    }


}
