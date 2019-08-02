<?php
/**
 * Role entity.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Role class.
 *
 * @ORM\Entity(repositoryClass="App\Repository\RoleRepository")
 * @ORM\Table(name="role")
 */
class Role
{
    /**
     * Primary key.
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id_roli", type="integer")
     */
    private $id;

    /**
     * Name.
     *
     * @ORM\Column(name="nazwa_roli", type="string", length=45)
     */
    private $name;

    /**
     * Gets id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets name.
     *
     * @return string|null Name
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Sets name.
     *
     * @param string $name New name
     *
     * @return Role
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
