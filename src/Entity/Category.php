<?php
/**
 * Category entity.
 */
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Category.
 *
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * Primary key.
     *
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id_kategorii", type="integer")
     */
    private $id;

    /**
     * Category name
     *
     * @var string
     * @ORM\Column(name="nazwa_kategorii", type="string", length=45)
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
}
