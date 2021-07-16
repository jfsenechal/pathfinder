<?php


namespace AfmLibre\Pathfinder\Entity;

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
 * @ORM\Entity(repositoryClass=SpellProfileRepository::class)
 * UniqueEntity(fields={"character_player", "spell"}, message="Sort déjà dans votre sélection")
 */
class SpellProfile implements SluggableInterface, TimestampableInterface
{
    use IdTrait;
    use NameTrait;
    use UuidTrait;
    use SluggableTrait;
    use TimestampableTrait;
    use SlugTrait;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected ?string $description;

    /**
     * @ORM\ManyToOne(targetEntity=Character::class, inversedBy="character_spell_profiles")
     * @ORM\JoinColumn(name="character_id", nullable=false)
     */
    private ?Character $character_player;

    /**
     * @ORM\OneToMany(targetEntity=SpellProfileCharacterSpell::class, mappedBy="spell_profile")
     * @var CharacterSpell[]
     */
    private iterable $character_spells;

    public function __construct(Character $character)
    {
        $this->character_spells = new ArrayCollection();
        $this->character_player = $character;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function shouldGenerateUniqueSlugs(): bool
    {
        return true;
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
     * @return Collection|CharacterSpell[]
     */
    public function setCharacterSpells(iterable $characterSpells): self
    {
        $this->character_spells = $characterSpells;

        return $this;
    }

    /**
     * @return Collection|SpellProfileCharacterSpell[]
     */
    public function getCharacterSpells(): Collection
    {
        return $this->character_spells;
    }

    public function addCharacterSpell(SpellProfileCharacterSpell $characterSpell): self
    {
        if (!$this->character_spells->contains($characterSpell)) {
            $this->character_spells[] = $characterSpell;
            $characterSpell->setSpellProfile($this);
        }

        return $this;
    }

    public function removeCharacterSpell(SpellProfileCharacterSpell $characterSpell): self
    {
        if ($this->character_spells->removeElement($characterSpell)) {
            // set the owning side to null (unless already changed)
            if ($characterSpell->getSpellProfile() === $this) {
                $characterSpell->setSpellProfile(null);
            }
        }

        return $this;
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
}
