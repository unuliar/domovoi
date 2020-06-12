<?php

namespace App\Entity;

use App\Repository\PollRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PollRepository::class)
 */
class Poll
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=AccountPollResult::class, mappedBy="poll", orphanRemoval=true)
     */
    private $PollResults;

    public function __construct()
    {
        $this->PollResults = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|AccountPollResult[]
     */
    public function getPollResults(): Collection
    {
        return $this->PollResults;
    }

    public function addPollResult(AccountPollResult $pollResult): self
    {
        if (!$this->PollResults->contains($pollResult)) {
            $this->PollResults[] = $pollResult;
            $pollResult->setPoll($this);
        }

        return $this;
    }

    public function removePollResult(AccountPollResult $pollResult): self
    {
        if ($this->PollResults->contains($pollResult)) {
            $this->PollResults->removeElement($pollResult);
            // set the owning side to null (unless already changed)
            if ($pollResult->getPoll() === $this) {
                $pollResult->setPoll(null);
            }
        }

        return $this;
    }
}
