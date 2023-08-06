<?php


namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Ancestry\SizeEnum;
use AfmLibre\Pathfinder\Character\Repository\CharacterRepository;
use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Entity\Traits\NameTrait;
use AfmLibre\Pathfinder\Entity\Traits\SlugTrait;
use AfmLibre\Pathfinder\Entity\Traits\UuidTrait;
use AfmLibre\Pathfinder\Modifier\ModifierCalculator;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\SluggableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Sluggable\SluggableTrait;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Symfony\Component\Validator\Constraints as Assert;

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

    private const maxAbilityValue = 30;

    #[ORM\Column(type: 'text', nullable: true)]
    public ?string $description = null;

    #[ORM\Column(type: 'smallint', nullable: false)]
    #[Assert\Range(min: 1, max: self::maxAbilityValue)]
    public int $strength = 10;
    #[ORM\Column(type: 'smallint', nullable: false)]
    #[Assert\Range(min: 1, max: self::maxAbilityValue)]
    public int $dexterity = 10;
    #[ORM\Column(type: 'smallint', nullable: false)]
    #[Assert\Range(min: 1, max: self::maxAbilityValue)]
    public int $constitution = 10;
    #[ORM\Column(type: 'smallint', nullable: false)]
    #[Assert\Range(min: 1, max: self::maxAbilityValue)]
    public int $intelligence = 10;
    #[ORM\Column(type: 'smallint', nullable: false)]
    #[Assert\Range(min: 1, max: self::maxAbilityValue)]
    public int $wisdom = 10;
    #[ORM\Column(type: 'smallint', nullable: false)]
    #[Assert\Range(min: 1, max: self::maxAbilityValue)]
    public int $charisma = 10;

    #[ORM\Column(type: 'smallint', nullable: false)]
    public int $hit_point = 0;
    #[ORM\Column(type: 'smallint', nullable: false)]
    public int $speed = 9;
    #[ORM\Column(nullable: true)]
    public ?string $alignment = null;
    #[ORM\Column(nullable: true)]
    public ?string $divinity = null;

    #[ORM\Column(nullable: false)]
    public SizeEnum $sizeType;
    #[ORM\Column(nullable: true)]
    public ?string $sex;
    #[ORM\Column(type: 'smallint', nullable: false)]
    public int $age = 0;
    #[ORM\Column(type: 'smallint', nullable: false)]
    public int $height = 0;
    #[ORM\Column(type: 'smallint', nullable: false)]
    public int $weight = 0;
    #[ORM\Column(nullable: true)]
    public ?string $hair = null;
    #[ORM\Column(nullable: true)]
    public ?string $eyes = null;
    #[ORM\Column(type: 'json', nullable: false)]
    public array $languages = [];
    #[ORM\Column(nullable: false)]
    public int $experience = 0;

    #[ORM\Column(nullable: false)]
    public int $moneyCP = 0;
    #[ORM\Column(nullable: false)]
    public int $moneySP = 0;
    #[ORM\Column(nullable: false)]
    public int $moneyGP = 0;
    #[ORM\Column(nullable: false)]
    public int $moneyPP = 0;

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

    #[ORM\ManyToOne(targetEntity: Armor::class)]
    #[ORM\JoinColumn(nullable: true)]
    public ?Armor $armor = null;

    public int $select_level;

    /**
     * @var Item[]
     */
    public array $items = [];

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

}
