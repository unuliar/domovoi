<?php

namespace App\Entity;

use App\Repository\OrganisationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrganisationRepository::class)
 */
class Organisation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $svcPhones = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fullName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=1024, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $site;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $staffCount;

    /**
     * @ORM\Column(type="integer")
     */
    private $houseCount;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $managedSquare;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $respectIndex;

    /**
     * @ORM\Column(type="integer")
     */
    private $rejectedHousesCount;

    /**
     * @ORM\Column(type="integer")
     */
    private $claimsCount;


    /**
     * @ORM\OneToMany(targetEntity=House::class, mappedBy="org")
     */
    private $houses;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $licenseDate;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $schedule = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true, unique=true)
     */
    private $rootObjId;

    public function __construct()
    {
        $this->houses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSvcPhones(): ?array
    {
        return $this->svcPhones;
    }

    public function setSvcPhones(?array $svcPhones): self
    {
        $this->svcPhones = $svcPhones;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getSite(): ?string
    {
        return $this->site;
    }

    public function setSite(?string $site): self
    {
        $this->site = $site;

        return $this;
    }

    public function getStaffCount(): ?string
    {
        return $this->staffCount;
    }

    public function setStaffCount(?string $staffCount): self
    {
        $this->staffCount = $staffCount;

        return $this;
    }

    public function getHouseCount(): ?int
    {
        return $this->houseCount;
    }

    public function setHouseCount(int $houseCount): self
    {
        $this->houseCount = $houseCount;

        return $this;
    }

    public function getManagedSquare(): ?int
    {
        return $this->managedSquare;
    }

    public function setManagedSquare(?int $managedSquare): self
    {
        $this->managedSquare = $managedSquare;

        return $this;
    }

    public function getRespectIndex(): ?float
    {
        return $this->respectIndex;
    }

    public function setRespectIndex(?float $respectIndex): self
    {
        $this->respectIndex = $respectIndex;

        return $this;
    }

    public function getRejectedHousesCount(): ?int
    {
        return $this->rejectedHousesCount;
    }

    public function setRejectedHousesCount(int $rejectedHousesCount): self
    {
        $this->rejectedHousesCount = $rejectedHousesCount;

        return $this;
    }

    public function getClaimsCount(): ?int
    {
        return $this->claimsCount;
    }

    public function setClaimsCount(int $claimsCount): self
    {
        $this->claimsCount = $claimsCount;

        return $this;
    }

    /**
     * @return Collection|House[]
     */
    public function getHouses(): Collection
    {
        return $this->houses;
    }

    public function addHouse(House $house): self
    {
        if (!$this->houses->contains($house)) {
            $this->houses[] = $house;
            $house->setOrg($this);
        }

        return $this;
    }

    public function removeHouse(House $house): self
    {
        if ($this->houses->contains($house)) {
            $this->houses->removeElement($house);
            // set the owning side to null (unless already changed)
            if ($house->getOrg() === $this) {
                $house->setOrg(null);
            }
        }

        return $this;
    }

    public function getLicenseDate(): ?\DateTimeInterface
    {
        return $this->licenseDate;
    }

    public function setLicenseDate(?\DateTimeInterface $licenseDate): self
    {
        $this->licenseDate = $licenseDate;

        return $this;
    }

    public function getSchedule(): ?array
    {
        return $this->schedule;
    }

    public function setSchedule(?array $schedule): self
    {
        $this->schedule = $schedule;

        return $this;
    }

    public function getRootObjId(): ?string
    {
        return $this->rootObjId;
    }

    public function setRootObjId(?string $rootObjId): self
    {
        $this->rootObjId = $rootObjId;

        return $this;
    }
}
