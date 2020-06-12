<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AccountRepository::class)
 */
class Account
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $vkId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=1024, nullable=true)
     */
    private $photoUrl;

    /**
     * @ORM\Column(type="string", length=1024)
     */
    private $vkToken;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $birthDate;

    /**
     * @ORM\ManyToMany(targetEntity=PersonClaim::class, mappedBy="accounts")
     */
    private $house;

    /**
     * @ORM\OneToMany(targetEntity=Letter::class, mappedBy="fromAcc")
     */
    private $letters;

    /**
     * @ORM\OneToMany(targetEntity=Letter::class, mappedBy="toAcc")
     */
    private $recievedLetters;

    /**
     * @ORM\ManyToMany(targetEntity=Notification::class, mappedBy="targets")
     */
    private $notifications;

    public function __construct()
    {
        $this->house = new ArrayCollection();
        $this->letters = new ArrayCollection();
        $this->recievedLetters = new ArrayCollection();
        $this->notifications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVkId(): ?int
    {
        return $this->vkId;
    }

    public function setVkId(?int $vkId): self
    {
        $this->vkId = $vkId;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPhotoUrl(): ?string
    {
        return $this->photoUrl;
    }

    public function setPhotoUrl(?string $photoUrl): self
    {
        $this->photoUrl = $photoUrl;

        return $this;
    }

    public function getVkToken(): ?string
    {
        return $this->vkToken;
    }

    public function setVkToken(string $vkToken): self
    {
        $this->vkToken = $vkToken;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(?\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * @return Collection|PersonClaim[]
     */
    public function getHouse(): Collection
    {
        return $this->house;
    }

    public function addHouse(PersonClaim $house): self
    {
        if (!$this->house->contains($house)) {
            $this->house[] = $house;
            $house->addAccount($this);
        }

        return $this;
    }

    public function removeHouse(PersonClaim $house): self
    {
        if ($this->house->contains($house)) {
            $this->house->removeElement($house);
            $house->removeAccount($this);
        }

        return $this;
    }

    /**
     * @return Collection|Letter[]
     */
    public function getLetters(): Collection
    {
        return $this->letters;
    }

    public function addLetter(Letter $letter): self
    {
        if (!$this->letters->contains($letter)) {
            $this->letters[] = $letter;
            $letter->setFromAcc($this);
        }

        return $this;
    }

    public function removeLetter(Letter $letter): self
    {
        if ($this->letters->contains($letter)) {
            $this->letters->removeElement($letter);
            // set the owning side to null (unless already changed)
            if ($letter->getFromAcc() === $this) {
                $letter->setFromAcc(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Letter[]
     */
    public function getRecievedLetters(): Collection
    {
        return $this->recievedLetters;
    }

    public function addRecievedLetter(Letter $recievedLetter): self
    {
        if (!$this->recievedLetters->contains($recievedLetter)) {
            $this->recievedLetters[] = $recievedLetter;
            $recievedLetter->setToAcc($this);
        }

        return $this;
    }

    public function removeRecievedLetter(Letter $recievedLetter): self
    {
        if ($this->recievedLetters->contains($recievedLetter)) {
            $this->recievedLetters->removeElement($recievedLetter);
            // set the owning side to null (unless already changed)
            if ($recievedLetter->getToAcc() === $this) {
                $recievedLetter->setToAcc(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Notification[]
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->addTarget($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->contains($notification)) {
            $this->notifications->removeElement($notification);
            $notification->removeTarget($this);
        }

        return $this;
    }
}
