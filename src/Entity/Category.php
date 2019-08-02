<?php
/**
 * Category entity.
 */
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Category.
 *
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 * @ORM\Table(name="kategorie")
 */
class Category
{
    /**
     * Primary key.
     *
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id_kategorie", type="integer")
     */
    private $id;

    /**
     * Category name
     *
     * @var string
     * @ORM\Column(name="nazwa_kategorii", type="string", length=45)
     */
    private $name;

    /**
     * Product types associated with this category.
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Type", mappedBy="category", fetch="EXTRA_LAZY")
     */
    private $types;

    public function __construct()
    {
        $this->types = new ArrayCollection();
    }

    /**
     * Gets id.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets name.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Sets name.
     *
     * @param string $name
     *
     * @return Category
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets types associated with this category.
     *
     * @return Collection|Type[]
     */
    public function getTypes(): Collection
    {
        return $this->types;
    }

    /**
     * Assigns type to this category.
     *
     * @param Type $type
     *
     * @return Category
     */
    public function addType(Type $type): self
    {
        if (!$this->types->contains($type)) {
            $this->types[] = $type;
            $type->setCategory($this);
        }

        return $this;
    }

    /**
     * Removes type from this category.
     *
     * @param Type $type
     *
     * @return Category
     */
    public function removeType(Type $type): self
    {
        if ($this->types->contains($type)) {
            $this->types->removeElement($type);
            // set the owning side to null (unless already changed)
            if ($type->getCategory() === $this) {
                $type->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * Converts to string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->getName();
    }
}
