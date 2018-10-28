<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JobRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Job
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=16)
     * @Groups({"api"})
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Groups({"api"})
     */
    private $dateIncoming;

    /**
     * @ORM\Column(type="date")
     * @Groups({"api"})
     */
    private $dateDeadline;

    /**
     * @ORM\ManyToOne(targetEntity="DeliveryType")
     * @Groups({"api"})
     */
    private $deliveryType;

    /**
     * @ORM\ManyToOne(targetEntity="Customer")
     * @Groups({"api"})
     */
    private $customer;

    /**
     * @ORM\Column(type="text")
     * @Groups({"api"})
     */
    private $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"api"})
     */
    private $notes;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"api"})
     */
    private $externalPurchase;

    /**
     * @ORM\Column(type="string", length=16, nullable=true)
     * @Groups({"api"})
     */
    private $invoiceNumber;

    /**
     * Many Jobs have Many Arrangers.
     * @ORM\ManyToMany(targetEntity="User", inversedBy="jobs")
     * @Groups({"api"})
     */
    private $arrangers;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups({"api"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"api"})
     */
    private $updatedAt;

    public function __construct() {
        $this->arrangers = new ArrayCollection();
    }

    public function setId(String $id): self {
        $this->id = $id;
        return $this;
    }

    public function getId() {
        return $this->id;
    }

    public function getDateIncoming(): ?\DateTime {
        return $this->dateIncoming;
    }

    public function setDateIncoming(\DateTime $dateIncoming): self {
        $this->dateIncoming = $dateIncoming;

        return $this;
    }

    public function getDateDeadline(): ?\DateTimeInterface {
        return $this->dateDeadline;
    }

    public function setDateDeadline(\DateTimeInterface $dateDeadline): self {
        $this->dateDeadline = $dateDeadline;

        return $this;
    }

    public function getDeliveryType(): ?DeliveryType {
        return $this->deliveryType;
    }

    public function setDeliveryType(DeliveryType $deliveryType): self {
        $this->deliveryType = $deliveryType;

        return $this;
    }

    public function getCustomer(): ?Customer {
        return $this->customer;
    }

    public function setCustomer(Customer $customer): self {
        $this->customer = $customer;

        return $this;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setDescription(string $description): self {
        $this->description = $description;

        return $this;
    }

    public function getNotes(): ?string {
        return $this->notes;
    }

    public function setNotes(?string $notes): self {
        $this->notes = $notes;

        return $this;
    }

    public function getExternalPurchase(): ?string {
        return $this->externalPurchase;
    }

    public function setExternalPurchase(?string $externalPurchase): self {
        $this->externalPurchase = $externalPurchase;

        return $this;
    }

    public function getInvoiceNumber(): ?string {
        return $this->invoiceNumber;
    }

    public function setInvoiceNumber(?string $invoiceNumber): self {
        $this->invoiceNumber = $invoiceNumber;
        return $this;
    }

    public function setArrangers(ArrayCollection $arrangers): self {
        $this->arrangers = $arrangers;
    }

    public function addArranger(User $arranger): self {
        $this->arrangers->add($arranger);
        return $this;
    }

    public function removeArranger(User $arranger): self {
        $this->arrangers->removeElement($arranger);
        return $this;
    }

    public function updateArrangers($arrangers) {
        foreach($this->arrangers as $arranger) {
            if (!array_search($arranger->getId(), array_map(function($element) { return $element->getId(); }, $arrangers))) {
                $this->removeArranger($arranger);
            }
        }
        foreach($arrangers as $arranger) {
            if (!array_search($arranger->getId(), array_map(function($element) { return $element->getId(); }, $this->arrangers->toArray()))) {
                $this->addArranger($arranger);
            }
        }
    }

    public function getArrangers(): Collection {
        return $this->arrangers;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable {
        return $this->createdAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): self {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getUpdatedAt(): \DateTime {
        return $this->updatedAt;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps() {
        $this->setUpdatedAt(new \DateTime('now'));

        if ($this->getCreatedAt() == null) {
            $this->setCreatedAt(new \DateTimeImmutable('now'));
        }
    }
}
