<?php


namespace App\Entity;

use App\Repository\RatingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RatingRepository::class)]
class Rating
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: "integer")]
    private ?int $stars = null; // Rating stars (0-5)

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $comment = null; // User's comment

    #[ORM\ManyToOne(targetEntity: Forum::class, inversedBy: "ratings")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Forum $forum = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "ratings")]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user = null;

    // Getter and setter methods
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStars(): ?int
    {
        return $this->stars;
    }

    public function setStars(int $stars): static
    {
        $this->stars = $stars;
        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;
        return $this;
    }

    public function getForum(): ?Forum
    {
        return $this->forum;
    }

    public function setForum(?Forum $forum): static
    {
        $this->forum = $forum;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }
}