<?php


namespace AfmLibre\Pathfinder\Entity;


use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Entity\Traits\NameTrait;
use AfmLibre\Pathfinder\Entity\Traits\SlugTrait;
use AfmLibre\Pathfinder\Entity\Traits\UuidTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use AfmLibre\Pathfinder\Repository\CharacterRepository;
use Knp\DoctrineBehaviors\Contract\Entity\SluggableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Sluggable\SluggableTrait;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;

#[ORM\Table(name: 'characters')]
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

    #[ORM\ManyToOne(targetEntity: Race::class, inversedBy: 'characters')]
    public ?Race $race = null;

    #[ORM\ManyToOne(targetEntity: CharacterClass::class, inversedBy: 'characters')]
    public ?CharacterClass $characterClass = null;

    /**
     * @var CharacterSpell[]
     */
    #[ORM\OneToMany(targetEntity: CharacterSpell::class, mappedBy: 'character_player', orphanRemoval: true)]
    public iterable $character_spells_available;

    /**
     * @var SpellProfile[]
     */
    #[ORM\OneToMany(targetEntity: SpellProfile::class, mappedBy: 'character_player', orphanRemoval: true)]
    public iterable $character_spell_profiles;

    public function __construct()
    {
        $this->character_spells_available = new ArrayCollection();
        $this->character_spell_profiles = new ArrayCollection();
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

    public function getStrength(): ?int
    {
        return $this->strength;
    }

    public function setStrength(int $strength): self
    {
        $this->strength = $strength;

        return $this;
    }

    public function getDexterity(): ?int
    {
        return $this->dexterity;
    }

    public function setDexterity(int $dexterity): self
    {
        $this->dexterity = $dexterity;

        return $this;
    }

    public function getConstitution(): ?int
    {
        return $this->constitution;
    }

    public function setConstitution(int $constitution): self
    {
        $this->constitution = $constitution;

        return $this;
    }

    public function getIntelligence(): ?int
    {
        return $this->intelligence;
    }

    public function setIntelligence(int $intelligence): self
    {
        $this->intelligence = $intelligence;

        return $this;
    }

    public function getWisdom(): ?int
    {
        return $this->wisdom;
    }

    public function setWisdom(int $wisdom): self
    {
        $this->wisdom = $wisdom;

        return $this;
    }

    public function getCharisma(): ?int
    {
        return $this->charisma;
    }

    public function setCharisma(int $charisma): self
    {
        $this->charisma = $charisma;

        return $this;
    }

    public function getRace(): ?Race
    {
        return $this->race;
    }

    public function setRace(?Race $race): self
    {
        $this->race = $race;

        return $this;
    }

    public function getCharacterClass(): ?CharacterClass
    {
        return $this->characterClass;
    }

    public function setCharacterClass(?CharacterClass $characterClass): self
    {
        $this->characterClass = $characterClass;

        return $this;
    }

    /**
     * @return Collection|CharacterSpell[]
     */
    public function getCharacterSpellsAvailable(): Collection
    {
        return $this->character_spells_available;
    }

    public function addSpell(CharacterSpell $spell): self
    {
        if (!$this->character_spells_available->contains($spell)) {
            $this->character_spells_available[] = $spell;
            $spell->setCharacterPlayer($this);
        }

        return $this;
    }

    public function removeSpell(CharacterSpell $spell): self
    {
        // set the owning side to null (unless already changed)
        if ($this->character_spells_available->removeElement($spell) && $spell->getCharacterPlayer() === $this) {
            $spell->setCharacterPlayer(null);
        }

        return $this;
    }

    public function addCharacterSpellsAvailable(CharacterSpell $characterSpellsAvailable): self
    {
        if (!$this->character_spells_available->contains($characterSpellsAvailable)) {
            $this->character_spells_available[] = $characterSpellsAvailable;
            $characterSpellsAvailable->setCharacterPlayer($this);
        }

        return $this;
    }

    public function removeCharacterSpellsAvailable(CharacterSpell $characterSpellsAvailable): self
    {
        // set the owning side to null (unless already changed)
        if ($this->character_spells_available->removeElement($characterSpellsAvailable) && $characterSpellsAvailable->getCharacterPlayer() === $this) {
            $characterSpellsAvailable->setCharacterPlayer(null);
        }

        return $this;
    }

    /**
     * @return Collection|SpellProfile[]
     */
    public function getCharacterSpellProfiles(): Collection
    {
        return $this->character_spell_profiles;
    }

    public function addCharacterSpellProfile(SpellProfile $characterSpellProfile): self
    {
        if (!$this->character_spell_profiles->contains($characterSpellProfile)) {
            $this->character_spell_profiles[] = $characterSpellProfile;
            $characterSpellProfile->setCharacterPlayer($this);
        }

        return $this;
    }

    public function removeCharacterSpellProfile(SpellProfile $characterSpellProfile): self
    {
        // set the owning side to null (unless already changed)
        if ($this->character_spell_profiles->removeElement($characterSpellProfile) && $characterSpellProfile->getCharacterPlayer() === $this) {
            $characterSpellProfile->setCharacterPlayer(null);
        }

        return $this;
    }

}
