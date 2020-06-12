<?php

namespace App\Entity;

use App\Repository\NotificationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NotificationRepository::class)
 */
class Notification
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Account::class, inversedBy="notifications")
     */
    private $targets;

    /**
     * @ORM\Column(type="string", length=4096)
     */
    private $body;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status = "CREATED";

    public function __construct()
    {
        $this->targets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Account[]
     */
    public function getTargets(): Collection
    {
        return $this->targets;
    }

    public function addTarget(Account $target): self
    {
        if (!$this->targets->contains($target)) {
            $this->targets[] = $target;
        }

        return $this;
    }

    public function removeTarget(Account $target): self
    {
        if ($this->targets->contains($target)) {
            $this->targets->removeElement($target);
        }

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
