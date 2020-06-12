<?php

namespace App\Entity;

use App\Repository\PersonClaimRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PersonClaimRepository::class)
 *///собственность
class PersonClaim
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $detailedAddress;

    /**
     * @ORM\ManyToMany(targetEntity=Account::class, inversedBy="house")
     */
    private $accounts;

    /**
     * @ORM\ManyToOne(targetEntity=House::class, inversedBy="personClaims")
     * @ORM\JoinColumn(nullable=false)
     */
    private $house;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $size;

    public function __construct()
    {
        $this->accounts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDetailedAddress(): ?string
    {
        return $this->detailedAddress;
    }

    public function setDetailedAddress(?string $detailedAddress): self
    {
        $this->detailedAddress = $detailedAddress;

        return $this;
    }

    /**
     * @return Collection|Account[]
     */
    public function getAccounts(): Collection
    {
        return $this->accounts;
    }

    public function addAccount(Account $account): self
    {
        if (!$this->accounts->contains($account)) {
            $this->accounts[] = $account;
        }

        return $this;
    }

    public function removeAccount(Account $account): self
    {
        if ($this->accounts->contains($account)) {
            $this->accounts->removeElement($account);
        }

        return $this;
    }

    public function getHouse(): ?House
    {
        return $this->house;
    }

    public function setHouse(?House $house): self
    {
        $this->house = $house;

        return $this;
    }

    public function getSize(): ?float
    {
        return $this->size;
    }

    public function setSize(?float $size): self
    {
        $this->size = $size;

        return $this;
    }
}
