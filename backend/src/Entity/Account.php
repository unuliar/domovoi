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
    private $ownings;

    /**
     * @ORM\ManyToMany(targetEntity=Notification::class, mappedBy="targets")
     */
    private $notifications;

    /**
     * @ORM\OneToMany(targetEntity=Letter::class, mappedBy="creator", orphanRemoval=true)
     */
    private $createdLetters;

    /**
     * @ORM\OneToMany(targetEntity=Letter::class, mappedBy="reciever", orphanRemoval=true)
     */
    private $recievedLetters;

    /**
     * @ORM\ManyToMany(targetEntity=Letter::class, mappedBy="signedAccounts")
     */
    private $signedLetters;

    /**
     * @ORM\ManyToMany(targetEntity=Meeting::class, mappedBy="Participants")
     */
    private $meetings;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type = "OWNER";

    /**
     * @ORM\OneToMany(targetEntity=Meeting::class, mappedBy="initiator", orphanRemoval=true)
     */
    private $createdMeetings;

    /**
     * @ORM\ManyToOne(targetEntity=Organisation::class, inversedBy="workers")
     */
    private $Org;

    /**
     * @ORM\OneToMany(targetEntity=Letter::class, mappedBy="worker")
     */
    private $assignedLetters;

    public function __construct()
    {
        $this->ownings = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->createdLetters = new ArrayCollection();
        $this->recievedLetters = new ArrayCollection();
        $this->signedLetters = new ArrayCollection();
        $this->meetings = new ArrayCollection();
        $this->assignedLetters = new ArrayCollection();
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
    public function getOwnings(): Collection
    {
        return $this->ownings;
    }

    public function addOwning(PersonClaim $house): self
    {
        if (!$this->ownings->contains($house)) {
            $this->ownings[] = $house;
            $house->addAccount($this);
        }

        return $this;
    }

    public function removeOwning(PersonClaim $house): self
    {
        if ($this->ownings->contains($house)) {
            $this->ownings->removeElement($house);
            $house->removeAccount($this);
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

    /**
     * @return Collection|Letter[]
     */
    public function getCreatedLetters(): Collection
    {
        return $this->createdLetters;
    }

    public function addCreatedLetter(Letter $createdLetter): self
    {
        if (!$this->createdLetters->contains($createdLetter)) {
            $this->createdLetters[] = $createdLetter;
            $createdLetter->setCreator($this);
        }

        return $this;
    }

    public function removeCreatedLetter(Letter $createdLetter): self
    {
        if ($this->createdLetters->contains($createdLetter)) {
            $this->createdLetters->removeElement($createdLetter);
            // set the owning side to null (unless already changed)
            if ($createdLetter->getCreator() === $this) {
                $createdLetter->setCreator(null);
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
            $recievedLetter->setReciever($this);
        }

        return $this;
    }

    public function removeRecievedLetter(Letter $recievedLetter): self
    {
        if ($this->recievedLetters->contains($recievedLetter)) {
            $this->recievedLetters->removeElement($recievedLetter);
            // set the owning side to null (unless already changed)
            if ($recievedLetter->getReciever() === $this) {
                $recievedLetter->setReciever(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Letter[]
     */
    public function getSignedLetters(): Collection
    {
        return $this->signedLetters;
    }

    public function addSignedLetter(Letter $signedLetter): self
    {
        if (!$this->signedLetters->contains($signedLetter)) {
            $this->signedLetters[] = $signedLetter;
            $signedLetter->addSignedAccount($this);
        }

        return $this;
    }

    public function removeSignedLetter(Letter $signedLetter): self
    {
        if ($this->signedLetters->contains($signedLetter)) {
            $this->signedLetters->removeElement($signedLetter);
            $signedLetter->removeSignedAccount($this);
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
            $meeting->addParticipant($this);
        }

        return $this;
    }

    public function removeMeeting(Meeting $meeting): self
    {
        if ($this->meetings->contains($meeting)) {
            $this->meetings->removeElement($meeting);
            $meeting->removeParticipant($this);
        }

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getOrg(): ?Organisation
    {
        return $this->Org;
    }

    public function setOrg(?Organisation $Org): self
    {
        $this->Org = $Org;

        return $this;
    }

    /**
     * @return Collection|Letter[]
     */
    public function getAssignedLetters(): Collection
    {
        return $this->assignedLetters;
    }

    public function addAssignedLetter(Letter $assignedLetter): self
    {
        if (!$this->assignedLetters->contains($assignedLetter)) {
            $this->assignedLetters[] = $assignedLetter;
            $assignedLetter->setWorker($this);
        }

        return $this;
    }

    public function removeAssignedLetter(Letter $assignedLetter): self
    {
        if ($this->assignedLetters->contains($assignedLetter)) {
            $this->assignedLetters->removeElement($assignedLetter);
            // set the owning side to null (unless already changed)
            if ($assignedLetter->getWorker() === $this) {
                $assignedLetter->setWorker(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedMeetings()
    {
        return $this->createdMeetings;
    }

    /**
     * @param mixed $createdMeetings
     *
     * @return Account
     */
    public function setCreatedMeetings($createdMeetings)
    {
        $this->createdMeetings = $createdMeetings;

        return $this;
    }

}
