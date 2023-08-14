<?php


namespace AfmLibre\Pathfinder\Entity\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait CampaingTrait
{
    #[ORM\Column(type: Types::JSON, nullable: true)]
    public ?array $campaings;
}
