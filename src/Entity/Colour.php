<?php
/**
 * Colour entity.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Colour.
 *
 * @ORM\Entity(repositoryClass="App\Repository\ColourRepository")
 * @ORM\Table(name="kolory")
 */
class Colour
{
    /**
     * Primary key.
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id_koloru", type="integer")
     */
    private $id;

    /**
     * Colour name.
     *
     * @ORM\Column(name="kolor", type="string", length=45)
     */
    private $name;

    public function getId(): ?int
    {
        return $this->id;
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

    public function __toString(): string
    {
        return $this->getName();
    }
}
