<?php
/**
 * Product entity.
 */
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Product.
 *
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\Table(name="produkty")
 */
class Product
{
    /**
     * Primary key.
     *
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id_produktu", type="integer")
     */
    private $id;

    /**
     * Product name.
     *
     * @var string
     * @ORM\Column(name="nazwa_produktu", type="string", length=100)
     */
    private $name;

    /**
     * Product description.
     *
     * @var string|null
     * @ORM\Column(name="opis", type="string", length=150, nullable=true)
     */
    private $description;

    /**
     * Product category.
     *
     * @var \Category
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="products")
     * @ORM\JoinColumn(name="id_kategorii", referencedColumnName="id_kategorii", nullable=false)
     */
    private $category;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}
