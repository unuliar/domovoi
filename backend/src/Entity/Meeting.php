<?php

namespace App\Entity;

use App\Repository\MeetingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MeetingRepository::class)
 */
class Meeting
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=House::class, inversedBy="meetings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $house;

    /**
     * @ORM\ManyToMany(targetEntity=Account::class, inversedBy="meetings")
     */
    private $Participants;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status = "PENDING";

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $plannedDate;

    /**
     * @ORM\OneToMany(targetEntity=MeetingQuestion::class, mappedBy="meeting", orphanRemoval=true)
     */
    private $meetingQuestions;

    /**
     * @ORM\OneToMany(targetEntity=ChatMessage::class, mappedBy="meeting", orphanRemoval=true)
     */
    private $chatMessages;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $plannedEndDate;

    /**
     * @ORM\Column(type="string", length=4096, nullable=true)
     */
    private $description;

    public function __construct()
    {
        $this->Participants = new ArrayCollection();
        $this->meetingQuestions = new ArrayCollection();
        $this->chatMessages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|Account[]
     */
    public function getParticipants(): Collection
    {
        return $this->Participants;
    }

    public function addParticipant(Account $participant): self
    {
        if (!$this->Participants->contains($participant)) {
            $this->Participants[] = $participant;
        }

        return $this;
    }

    public function removeParticipant(Account $participant): self
    {
        if ($this->Participants->contains($participant)) {
            $this->Participants->removeElement($participant);
        }

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPlannedDate(): ?\DateTimeInterface
    {
        return $this->plannedDate;
    }

    public function setPlannedDate(?\DateTimeInterface $plannedDate): self
    {
        $this->plannedDate = $plannedDate;

        return $this;
    }

    /**
     * @return Collection|MeetingQuestion[]
     */
    public function getMeetingQuestions(): Collection
    {
        return $this->meetingQuestions;
    }

    public function addMeetingQuestion(MeetingQuestion $meetingQuestion): self
    {
        if (!$this->meetingQuestions->contains($meetingQuestion)) {
            $this->meetingQuestions[] = $meetingQuestion;
            $meetingQuestion->setMeeting($this);
        }

        return $this;
    }

    public function removeMeetingQuestion(MeetingQuestion $meetingQuestion): self
    {
        if ($this->meetingQuestions->contains($meetingQuestion)) {
            $this->meetingQuestions->removeElement($meetingQuestion);
            // set the owning side to null (unless already changed)
            if ($meetingQuestion->getMeeting() === $this) {
                $meetingQuestion->setMeeting(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ChatMessage[]
     */
    public function getChatMessages(): Collection
    {
        return $this->chatMessages;
    }

    public function addChatMessage(ChatMessage $chatMessage): self
    {
        if (!$this->chatMessages->contains($chatMessage)) {
            $this->chatMessages[] = $chatMessage;
            $chatMessage->setMeeting($this);
        }

        return $this;
    }

    public function removeChatMessage(ChatMessage $chatMessage): self
    {
        if ($this->chatMessages->contains($chatMessage)) {
            $this->chatMessages->removeElement($chatMessage);
            // set the owning side to null (unless already changed)
            if ($chatMessage->getMeeting() === $this) {
                $chatMessage->setMeeting(null);
            }
        }

        return $this;
    }

    public function getPlannedEndDate(): ?\DateTimeInterface
    {
        return $this->plannedEndDate;
    }

    public function setPlannedEndDate(?\DateTimeInterface $plannedEndDate): self
    {
        $this->plannedEndDate = $plannedEndDate;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
