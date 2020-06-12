<?php

namespace App\Entity;

use App\Repository\HouseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HouseRepository::class)
 */
class House
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $residentalPremiseCount = 0;

    /**
     * @ORM\Column(type="float")
     */
    private $residentialPremiseTotalSquare = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $buildingYear;

    /**
     * @ORM\Column(type="string", length=1024)
     */
    private $address = "";

    /**
     * @ORM\ManyToOne(targetEntity=Organisation::class, inversedBy="houses")
     */
    private $org;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, unique=true)
     */
    private $guid;

    /**
     * @ORM\OneToMany(targetEntity=PersonClaim::class, mappedBy="house")
     */
    private $personClaims;

    /**
     * @ORM\OneToMany(targetEntity=Meeting::class, mappedBy="house", orphanRemoval=true)
     */
    private $meetings;

    public function __construct()
    {
        $this->personClaims = new ArrayCollection();
        $this->meetings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getResidentalPremiseCount(): ?int
    {
        return $this->residentalPremiseCount;
    }

    public function setResidentalPremiseCount(int $residentalPremiseCount): self
    {
        $this->residentalPremiseCount = $residentalPremiseCount;

        return $this;
    }

    public function getResidentialPremiseTotalSquare(): ?float
    {
        return $this->residentialPremiseTotalSquare;
    }

    public function setResidentialPremiseTotalSquare(float $residentialPremiseTotalSquare): self
    {
        $this->residentialPremiseTotalSquare = $residentialPremiseTotalSquare;

        return $this;
    }

    public function getBuildingYear(): ?int
    {
        return $this->buildingYear;
    }

    public function setBuildingYear(int $buildingYear): self
    {
        $this->buildingYear = $buildingYear;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getOrg(): ?Organisation
    {
        return $this->org;
    }

    public function setOrg(?Organisation $org): self
    {
        $this->org = $org;

        return $this;
    }

    public function getGuid(): ?string
    {
        return $this->guid;
    }

    public function setGuid(?string $guid): self
    {
        $this->guid = $guid;

        return $this;
    }

    /**
     * @return Collection|PersonClaim[]
     */
    public function getPersonClaims(): Collection
    {
        return $this->personClaims;
    }

    public function addPersonClaim(PersonClaim $personClaim): self
    {
        if (!$this->personClaims->contains($personClaim)) {
            $this->personClaims[] = $personClaim;
            $personClaim->setHouse($this);
        }

        return $this;
    }

    public function removePersonClaim(PersonClaim $personClaim): self
    {
        if ($this->personClaims->contains($personClaim)) {
            $this->personClaims->removeElement($personClaim);
            // set the owning side to null (unless already changed)
            if ($personClaim->getHouse() === $this) {
                $personClaim->setHouse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Meeting[]
     */
    public function getMeetings(): Collection
    {
        return $this->meetings;
    }

    public function addMeeting(Meeting $meeting): self
    {
        if (!$this->meetings->contains($meeting)) {
            $this->meetings[] = $meeting;
            $meeting->setHouse($this);
        }

        return $this;
    }

    public function removeMeeting(Meeting $meeting): self
    {
        if ($this->meetings->contains($meeting)) {
            $this->meetings->removeElement($meeting);
            // set the owning side to null (unless already changed)
            if ($meeting->getHouse() === $this) {
                $meeting->setHouse(null);
            }
        }

        return $this;
    }
}
