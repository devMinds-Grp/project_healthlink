<?php

namespace App\Entity;

use App\Entity\Forum;
use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity]
class Report
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Forum::class, inversedBy: 'reports')]
    #[ORM\JoinColumn(nullable: false)]
    private Forum $forum;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private User $reportedBy;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $reportedAt;

    #[ORM\Column(type: 'string', length: 255)] // Ajout de la propriété reason
    #[Assert\NotBlank(message: "La raison est obligatoire.")]
    #[Assert\Length(
        min: 5,
        max: 100,
        minMessage: "La raison doit contenir au moins {{ limit }} caractères.",
        maxMessage: "La raison ne peut pas dépasser {{ limit }} caractères."
    )]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z0-9\s\-_?!]+$/",
        message: "La raison contient des caractères non autorisés."
    )]
    private ?string $reason = null;

    public function __construct()
    {
        $this->reportedAt = new \DateTime();
    }

    // Getters et Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getForum(): Forum
    {
        return $this->forum;
    }

    public function setForum(Forum $forum): self
    {
        $this->forum = $forum;
        return $this;
    }

    public function getReportedBy(): User
    {
        return $this->reportedBy;
    }

    public function setReportedBy(User $reportedBy): self
    {
        $this->reportedBy = $reportedBy;
        return $this;
    }

    public function getReportedAt(): \DateTimeInterface
    {
        return $this->reportedAt;
    }

    public function setReportedAt(\DateTimeInterface $reportedAt): self
    {
        $this->reportedAt = $reportedAt;
        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): self
    {
        $this->reason = $reason;
        return $this;
    }
}