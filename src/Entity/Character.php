<?php


namespace AfmLibre\Pathfinder\Entity;


use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Entity\Traits\NameTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use AfmLibre\Pathfinder\Repository\CharacterRepository;

/**
 * @ORM\Entity(repositoryClass=CharacterRepository::class)
 */
class Character
{
    use IdTrait;
    use NameTrait;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected ?string $description;

    /**
     * @ORM\Column(type="smallint", nullable=false)
     */
    protected int $strength;
    /**
     * @ORM\Column(type="smallint", nullable=false)
     */
    protected int $dexterity;
    /**
     * @ORM\Column(type="smallint", nullable=false)
     */
    protected int $constitution;
    /**
     * @ORM\Column(type="smallint", nullable=false)
     */
    protected int $intelligence;
    /**
     * @ORM\Column(type="smallint", nullable=false)
     */
    protected int $wisdom;
    /**
     * @ORM\Column(type="smallint", nullable=false)
     */
    protected int $charisma;

    /**
     * @ORM\ManyToOne(targetEntity=Race::class, inversedBy="characters")
     */
    private ?Race $race;

    /**
     * @ORM\ManyToOne(targetEntity=CharacterClass::class, inversedBy="characters")
     */
    private ?CharacterClass $characterClass;

    /**
     * @ORM\OneToMany(targetEntity=CharacterSpell::class, mappedBy="character_player", orphanRemoval=true)
     * @var CharacterSpell[]
     */
    private iterable $character_spells_available;

    /**
     * @ORM\OneToMany(targetEntity=SpellProfile::class, mappedBy="character_player", orphanRemoval=true)
     * @var SpellProfile[]
     */
    private iterable $character_spell_profiles;

    public function __construct()
    {
        $this->strength = 10;
        $this->dexterity = 10;
        $this->constitution = 10;
        $this->intelligence = 10;
        $this->wisdom = 10;
        $this->charisma = 10;
        $this->character_spells_available = new ArrayCollection();
        $this->character_spell_profiles = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
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
        if ($this->character_spells_available->removeElement($spell)) {
            // set the owning side to null (unless already changed)
            if ($spell->getCharacterPlayer() === $this) {
                $spell->setCharacterPlayer(null);
            }
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
        if ($this->character_spells_available->removeElement($characterSpellsAvailable)) {
            // set the owning side to null (unless already changed)
            if ($characterSpellsAvailable->getCharacterPlayer() === $this) {
                $characterSpellsAvailable->setCharacterPlayer(null);
            }
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
        if ($this->character_spell_profiles->removeElement($characterSpellProfile)) {
            // set the owning side to null (unless already changed)
            if ($characterSpellProfile->getCharacterPlayer() === $this) {
                $characterSpellProfile->setCharacterPlayer(null);
            }
        }

        return $this;
    }

}
