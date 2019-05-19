<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 * @ORM\Table(name="zamowienia")
 */
class Order
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id_zamowienia", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="data_zamowienia", type="datetime")
     */
    private $ordered_date;

    /**
     * @ORM\Column(name="data_realizacji", type="datetime", nullable=true)
     */
    private $shipped_date;

    /**
     * @ORM\Column(name="status", type="string", length=45, nullable=true)
     */
    private $status;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\DeliveryAddress", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="adresy_dostawy_id_adresy_dostawy", referencedColumnName="id_adresy_dostawy" , nullable=false)
     */
    private $address;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ShippingMethod")
     * @ORM\JoinColumn(name="id_wysylka", referencedColumnName="id_wysylka", nullable=false)
     */
    private $shipping_method;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PaymentMethod")
     * @ORM\JoinColumn(name="id_platnosci", referencedColumnName="id_platnosci", nullable=false)
     */
    private $payment;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderedDate(): ?\DateTimeInterface
    {
        return $this->ordered_date;
    }

    public function setOrderedDate(\DateTimeInterface $ordered_date): self
    {
        $this->ordered_date = $ordered_date;

        return $this;
    }

    public function getShippedDate(): ?\DateTimeInterface
    {
        return $this->shipped_date;
    }

    public function setShippedDate(?\DateTimeInterface $shipped_date): self
    {
        $this->shipped_date = $shipped_date;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getAddress(): ?DeliveryAddress
    {
        return $this->address;
    }

    public function setAddress(DeliveryAddress $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getShippingMethod(): ?ShippingMethod
    {
        return $this->shipping_method;
    }

    public function setShippingMethod(?ShippingMethod $shipping_method): self
    {
        $this->shipping_method = $shipping_method;

        return $this;
    }

    public function getPayment(): ?PaymentMethod
    {
        return $this->payment;
    }

    public function setPayment(?PaymentMethod $payment): self
    {
        $this->payment = $payment;

        return $this;
    }
}
