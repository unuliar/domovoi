<?php

namespace App\Entity;

use App\Repository\AttachmentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AttachmentRepository::class)
 */
class Attachment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=512)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $file;

    /**
     * @ORM\ManyToOne(targetEntity=Letter::class, inversedBy="attachments")
     */
    private $letter;

    /**
     * @ORM\ManyToOne(targetEntity=Post::class, inversedBy="attachments")
     */
    private $post;

    /**
     * @ORM\ManyToOne(targetEntity=MeetingQuestion::class, inversedBy="attachments")
     */
    private $meetingQuestion;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): self
    {
        $this->file = $file;

        return $this;
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

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getMeetingQuestion(): ?MeetingQuestion
    {
        return $this->meetingQuestion;
    }

    public function setMeetingQuestion(?MeetingQuestion $meetingQuestion): self
    {
        $this->meetingQuestion = $meetingQuestion;

        return $this;
    }
}
