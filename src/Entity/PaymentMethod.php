<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PaymentMethodRepository")
 * @ORM\Table(name="platnosci")
 */
class PaymentMethod
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id_platnosci", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="rodzaj_platnosci", type="string", length=25)
     */
    private $type;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
