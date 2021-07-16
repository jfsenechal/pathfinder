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
     * @var SpellProfileCharacterSpell[]
     */
    private iterable $spell_profile_character_spells;

    /**
     * @ORM\ManyToMany(targetEntity=CharacterSpell::class)
     * @\Doctrine\ORM\Mapping\JoinTable(name="titi")
     * @var CharacterSpell[]
     */
    private iterable $character_spells22;

    public function __construct(Character $character)
    {
        $this->spell_profile_character_spells = new ArrayCollection();
        $this->character_player = $character;
        $this->character_spells22 = new ArrayCollection();
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
    public function setSpellprofileCharacterSpells(iterable $characterSpells): self
    {
        $this->spell_profile_character_spells = $characterSpells;

        return $this;
    }

    /**
     * @return Collection|SpellProfileCharacterSpell[]
     */
    public function getSpellProfileCharacterSpells(): Collection
    {
        return $this->spell_profile_character_spells;
    }

    public function addCharacterSpell(SpellProfileCharacterSpell $characterSpell): self
    {
        if (!$this->spell_profile_character_spells->contains($characterSpell)) {
            $this->spell_profile_character_spells[] = $characterSpell;
            $characterSpell->setSpellProfile($this);
        }

        return $this;
    }

    public function removeCharacterSpell(SpellProfileCharacterSpell $characterSpell): self
    {
        if ($this->spell_profile_character_spells->removeElement($characterSpell)) {
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

    /**
     * @return Collection|CharacterSpell[]
     */
    public function getCharacterSpells22(): Collection
    {
        return $this->character_spells22;
    }

    public function addCharacterSpells22(CharacterSpell $characterSpells22): self
    {
        if (!$this->character_spells22->contains($characterSpells22)) {
            $this->character_spells22[] = $characterSpells22;
        }

        return $this;
    }

    public function removeCharacterSpells22(CharacterSpell $characterSpells22): self
    {
        $this->character_spells22->removeElement($characterSpells22);

        return $this;
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
