<?php

namespace App\Entity;

use App\Repository\LetterChangesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LetterChangesRepository::class)
 */
class LetterChanges
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Letter::class, inversedBy="letterChanges")
     * @ORM\JoinColumn(nullable=false)
     */
    private $letter;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $changetype;

    /**
     * @ORM\Column(type="string", length=1024, nullable=true)
     */
    private $fromValue;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=1024, nullable=true)
     */
    private $toValue;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLetter(): ?Letter
    {
        return $this->letter;
    }

    public function setLetter(?Letter $letter): self
    {
        $this->letter = $letter;

        return $this;
    }

    public function getChangetype(): ?string
    {
        return $this->changetype;
    }

    public function setChangetype(string $changetype): self
    {
        $this->changetype = $changetype;

        return $this;
    }

    public function getFromValue(): ?string
    {
        return $this->fromValue;
    }

    public function setFromValue(?string $fromValue): self
    {
        $this->fromValue = $fromValue;

        return $this;
    }

    public function getToValue(): ?string
    {
        return $this->toValue;
    }

    public function setToValue(?string $toValue): self
    {
        $this->toValue = $toValue;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }
}
