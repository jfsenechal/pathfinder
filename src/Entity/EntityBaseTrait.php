<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 25/03/19
 * Time: 10:33
 */

namespace AfmLibre\Pathfinder\Entity;


use Doctrine\ORM\Mapping as ORM;

trait EntityBaseTrait
{
    /**
     * @ORM\Column(type="string", length=150)
     */
    protected ?string $name;
    /**
     * @ORM\Column(type="text")
     */
    protected ?string $description;
    /**
     * @ORM\Column(type="string", length=150)
     */
    protected ?string $reference;
    /**
     * @ORM\Column(type="string", length=150)
     */
    protected ?string $source;
}
