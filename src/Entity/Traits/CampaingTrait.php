<?php


namespace AfmLibre\Pathfinder\Entity\Traits;


use Doctrine\ORM\Mapping as ORM;

trait CampaingTrait
{
    #[ORM\Column(type: 'json', nullable: true)]
    public ?array $campaings;

}
