<?php

namespace App\Entity;

use App\Repository\HouseRepository;
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
    private $residentalPremiseCount;

    /**
     * @ORM\Column(type="float")
     */
    private $residentialPremiseTotalSquare;

    /**
     * @ORM\Column(type="integer")
     */
    private $buildingYear;

    /**
     * @ORM\Column(type="string", length=1024)
     */
    private $address;

    /**
     * @ORM\ManyToOne(targetEntity=Organisation::class, inversedBy="houses")
     */
    private $org;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, unique=true)
     */
    private $guid;

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
}
