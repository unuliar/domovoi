<?php

namespace App\Entity;

use App\Repository\LetterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LetterRepository::class)
 */
class Letter
{
    public static $TYPE_INDIVIDUAL = 1;
    public static $TYPE_COMMUNITY = 2;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=512)
     */
    private $subject;

    /**
     * @ORM\Column(type="text")
     */
    private $body;

    /**
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="createdLetters")
     * @ORM\JoinColumn(nullable=false)
     */
    private $creator;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="recievedLetters")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reciever;

    /**
     * @ORM\ManyToMany(targetEntity=Account::class, inversedBy="signedLetters")
     */
    private $signedAccounts;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status = "Ожидает";

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\ManyToOne(targetEntity=House::class, inversedBy="letters")
     */
    private $house;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="assignedLetters")
     */
    private $worker;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $feedback;

    /**
     * @ORM\Column(type="string", length=1024, nullable=true)
     */
    private $feedbackComment;

    /**
     * @ORM\OneToMany(targetEntity=LetterChanges::class, mappedBy="letter", orphanRemoval=true)
     */
    private $letterChanges;

    /**
     * @ORM\OneToMany(targetEntity=Attachment::class, mappedBy="letter")
     */
    private $attachments;

    public function __construct()
    {
        $this->signedAccounts = new ArrayCollection();
        $this->letterChanges = new ArrayCollection();
        $this->attachments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCreator(): ?Account
    {
        return $this->creator;
    }

    public function setCreator(?Account $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    public function getReciever(): ?Account
    {
        return $this->reciever;
    }

    public function setReciever(?Account $reciever): self
    {
        $this->reciever = $reciever;

        return $this;
    }

    /**
     * @return Collection|Account[]
     */
    public function getSignedAccounts(): Collection
    {
        return $this->signedAccounts;
    }

    public function addSignedAccount(Account $signedAccount): self
    {
        if (!$this->signedAccounts->contains($signedAccount)) {
            $this->signedAccounts[] = $signedAccount;
        }

        return $this;
    }

    public function removeSignedAccount(Account $signedAccount): self
    {
        if ($this->signedAccounts->contains($signedAccount)) {
            $this->signedAccounts->removeElement($signedAccount);
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

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

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

    public function getWorker(): ?Account
    {
        return $this->worker;
    }

    public function setWorker(?Account $worker): self
    {
        $this->worker = $worker;

        return $this;
    }

    public function getFeedback(): ?int
    {
        return $this->feedback;
    }

    public function setFeedback(?int $feedback): self
    {
        $this->feedback = $feedback;

        return $this;
    }

    public function getFeedbackComment(): ?string
    {
        return $this->feedbackComment;
    }

    public function setFeedbackComment(?string $feedbackComment): self
    {
        $this->feedbackComment = $feedbackComment;

        return $this;
    }

    /**
     * @return Collection|LetterChanges[]
     */
    public function getLetterChanges(): Collection
    {
        return $this->letterChanges;
    }

    public function addLetterChange(LetterChanges $letterChange): self
    {
        if (!$this->letterChanges->contains($letterChange)) {
            $this->letterChanges[] = $letterChange;
            $letterChange->setLetter($this);
        }

        return $this;
    }

    public function removeLetterChange(LetterChanges $letterChange): self
    {
        if ($this->letterChanges->contains($letterChange)) {
            $this->letterChanges->removeElement($letterChange);
            // set the owning side to null (unless already changed)
            if ($letterChange->getLetter() === $this) {
                $letterChange->setLetter(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Attachment[]
     */
    public function getAttachments(): Collection
    {
        return $this->attachments;
    }

    public function addAttachment(Attachment $attachment): self
    {
        if (!$this->attachments->contains($attachment)) {
            $this->attachments[] = $attachment;
            $attachment->setLetter($this);
        }

        return $this;
    }

    public function removeAttachment(Attachment $attachment): self
    {
        if ($this->attachments->contains($attachment)) {
            $this->attachments->removeElement($attachment);
            // set the owning side to null (unless already changed)
            if ($attachment->getLetter() === $this) {
                $attachment->setLetter(null);
            }
        }

        return $this;
    }
}
