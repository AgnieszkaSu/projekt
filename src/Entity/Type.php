<?php
/**
 * Type entity.
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Type.
 *
 * @ORM\Entity(repositoryClass="App\Repository\TypeRepository")
 * @ORM\Table(name="rodzaj_produktu")
 */
class Type
{
    /**
     * Primary key.
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id_rodzaj_produktu", type="integer")
     */
    private $id;

    /**
     * Type name.
     *
     * @ORM\Column(name="nazwa", type="string", length=45)
     */
    private $name;

    /**
     * Type description.
     *
     * @ORM\Column(name="opis", type="string", length=200, nullable=true)
     */
    private $description;

    /**
     * Category this type belongs to.
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="types")
     * @ORM\JoinColumn(name="kategorie_id_kategorie", referencedColumnName="id_kategorie", nullable=false)
     */
    private $category;

    /**
     * Product's associated with this type.
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="type")
     */
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

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

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setType($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getType() === $this) {
                $product->setType(null);
            }
        }

        return $this;
    }
}
