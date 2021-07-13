<?php


namespace AfmLibre\Pathfinder\Entity;


use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use AfmLibre\Pathfinder\Repository\CharacterRepository;

/**
 * @ORM\Entity(repositoryClass=CharacterRepository::class)
 * @ORM\Table(name="characters")
 */
class Character
{
    use IdTrait;

    /**
     * @ORM\Column(type="string", length=150)
     */
    protected ?string $name;

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
     */
    private iterable $character_spells;

    public function __construct()
    {
        $this->strength = 10;
        $this->dexterity = 10;
        $this->constitution = 10;
        $this->intelligence = 10;
        $this->wisdom = 10;
        $this->charisma = 10;
        $this->character_spells = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
    public function getCharacterSpells(): Collection
    {
        return $this->character_spells;
    }

    public function addSpell(CharacterSpell $spell): self
    {
        if (!$this->character_spells->contains($spell)) {
            $this->character_spells[] = $spell;
            $spell->setCharacterPlayer($this);
        }

        return $this;
    }

    public function removeSpell(CharacterSpell $spell): self
    {
        if ($this->character_spells->removeElement($spell)) {
            // set the owning side to null (unless already changed)
            if ($spell->getCharacterPlayer() === $this) {
                $spell->setCharacterPlayer(null);
            }
        }

        return $this;
    }

}
