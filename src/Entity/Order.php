<?php
/**
 * Category entity.
 */

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class order.
 *
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 * @ORM\Table(name="zamowienia")
 */
class Order
{
    /**
     * Primary key.
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id_zamowienia", type="integer")
     */
    private $id;

    /**
     * Order date.
     *
     * @ORM\Column(name="data_zamowienia", type="datetime")
     */
    private $ordered_date;

    /**
     * Shipping date.
     *
     * @ORM\Column(name="data_realizacji", type="datetime", nullable=true)
     */
    private $shipped_date;

    /**
     * Order status.
     *
     * @ORM\Column(name="status", type="string", length=45, nullable=true)
     */
    private $status;

    /**
     * Order address.
     *
     * @ORM\OneToOne(targetEntity="App\Entity\DeliveryAddress", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="adresy_dostawy_id_adresy_dostawy", referencedColumnName="id_adresy_dostawy" , nullable=false)
     */
    private $address;

    /**
     * Shipping method.
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\ShippingMethod")
     * @ORM\JoinColumn(name="id_wysylka", referencedColumnName="id_wysylka", nullable=false)
     */
    private $shipping_method;

    /**
     * Payment method.
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\PaymentMethod")
     * @ORM\JoinColumn(name="id_platnosci", referencedColumnName="id_platnosci", nullable=false)
     */
    private $payment;

    /**
     * Customer.
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer", inversedBy="orders")
     * @ORM\JoinColumn(name="id_klienta", referencedColumnName="id_klienta")
     */
    private $customer;

    /**
     * Ordered products.
     *
     * @ORM\OneToMany(targetEntity="App\Entity\OrderProducts", mappedBy="order", orphanRemoval=true, fetch="EXTRA_LAZY", cascade={"persist"})
     */
    private $orderProducts;

    public function __construct()
    {
        $this->orderProducts = new ArrayCollection();
        $this->ordered_date = new DateTime();
        $this->status = 'zamówione';
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
     * Gets order date.
     *
     * @return DateTimeInterface|null
     */
    public function getOrderedDate(): ?DateTimeInterface
    {
        return $this->ordered_date;
    }

    /**
     * Sets order date.
     *
     * @param DateTimeInterface $ordered_date
     *
     * @return Order
     */
    public function setOrderedDate(DateTimeInterface $ordered_date): self
    {
        $this->ordered_date = $ordered_date;

        return $this;
    }

    /**
     * Gets shipping date.
     *
     * @return DateTimeInterface|null
     */
    public function getShippedDate(): ?DateTimeInterface
    {
        return $this->shipped_date;
    }

    /**
     * Sets shipping date.
     *
     * @param DateTimeInterface|null $shipped_date
     *
     * @return Order
     */
    public function setShippedDate(?DateTimeInterface $shipped_date): self
    {
        $this->shipped_date = $shipped_date;

        return $this;
    }

    /**
     * Gets status.
     *
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * Sets status.
     *
     * @param string|null $status
     *
     * @return Order
     */
    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Gets address.
     *
     * @return DeliveryAddress|null
     */
    public function getAddress(): ?DeliveryAddress
    {
        return $this->address;
    }

    /**
     * Sets address.
     *
     * @param DeliveryAddress $address
     *
     * @return Order
     */
    public function setAddress(DeliveryAddress $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Gets shipping method.
     *
     * @return ShippingMethod|null
     */
    public function getShippingMethod(): ?ShippingMethod
    {
        return $this->shipping_method;
    }

    /**
     * Sets shipping method.
     *
     * @param ShippingMethod|null $shipping_method
     *
     * @return Order
     */
    public function setShippingMethod(?ShippingMethod $shipping_method): self
    {
        $this->shipping_method = $shipping_method;

        return $this;
    }

    /**
     * Gets payment method.
     *
     * @return PaymentMethod|null
     */
    public function getPayment(): ?PaymentMethod
    {
        return $this->payment;
    }

    /**
     * Sets payment method.
     *
     * @param PaymentMethod|null $payment
     *
     * @return Order
     */
    public function setPayment(?PaymentMethod $payment): self
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * Gets customer.
     *
     * @return Customer|null
     */
    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    /**
     * Sets customer.
     *
     * @param Customer|null $customer
     *
     * @return Order
     */
    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Gets products.
     *
     * @return Collection|OrderProducts[]
     */
    public function getOrderProducts(): Collection
    {
        return $this->orderProducts;
    }

    /**
     * Add product to order.
     *
     * @param OrderProducts $orderProduct
     *
     * @return Order
     */
    public function addOrderProduct(OrderProducts $orderProduct): self
    {
        if (!$this->orderProducts->contains($orderProduct)) {
            $this->orderProducts[] = $orderProduct;
            $orderProduct->setOrder($this);
        }

        return $this;
    }

    /**
     * Remove product from order.
     *
     * @param OrderProducts $orderProduct
     *
     * @return Order
     */
    public function removeOrderProduct(OrderProducts $orderProduct): self
    {
        if ($this->orderProducts->contains($orderProduct)) {
            $this->orderProducts->removeElement($orderProduct);
            // set the owning side to null (unless already changed)
            if ($orderProduct->getOrder() === $this) {
                $orderProduct->setOrder(null);
            }
        }

        return $this;
    }
}
