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

    public function __construct()
    {
        $this->signedAccounts = new ArrayCollection();
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
}
