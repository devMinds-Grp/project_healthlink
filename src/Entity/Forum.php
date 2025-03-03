<?php

namespace App\Entity;

use App\Repository\ForumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ForumRepository::class)]
class Forum
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le titre est obligatoire.")]
    #[Assert\Length(
        min: 5,
        max: 100,
        minMessage: "Le titre doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le titre ne peut pas dépasser {{ limit }} caractères."
    )]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z0-9\s\-_?!]+$/",
        message: "Le titre contient des caractères non autorisés."
    )]
    private ?string $Title = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La description est obligatoire.")]
    #[Assert\Length(
        min: 10,
        max: 1000,
        minMessage: "La description doit contenir au moins {{ limit }} caractères.",
        maxMessage: "La description ne peut pas dépasser {{ limit }} caractères."
    )]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z0-9\s\-_?!.,\n\r]+$/",
        message: "La description contient des caractères non autorisés."
    )]
    private ?string $Description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $Date = null;

    #[ORM\ManyToOne(inversedBy: 'UserForum')]
    private ?User $user = null;

    /**
     * @var Collection<int, ForumResponse>
     */
    #[ORM\OneToMany(targetEntity: ForumResponse::class, mappedBy: 'forum', cascade: ['remove'])]
    private Collection $ForomRep;

    #[ORM\Column(type: 'boolean')]
    private bool $isApproved = false; // Par défaut, la publication n'est pas approuvée

    /**
     * @var Collection<int, Rating>
     */
    #[ORM\OneToMany(targetEntity: Rating::class, mappedBy: 'forum', cascade: ['remove'])]
    private Collection $ratings;

    /**
     * @var Collection<int, Report>
     */
    #[ORM\OneToMany(targetEntity: Report::class, mappedBy: 'forum', cascade: ['remove'])]
    private Collection $reports;

    public function __construct()
    {
        $this->ForomRep = new ArrayCollection();
        $this->ratings = new ArrayCollection();
        $this->reports = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): static
    {
        $this->Title = $Title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): static
    {
        $this->Description = $Description;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): static
    {
        $this->Date = $Date;

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

    /**
     * @return Collection<int, ForumResponse>
     */
    public function getForomRep(): Collection
    {
        return $this->ForomRep;
    }

    public function addForomRep(ForumResponse $foromRep): static
    {
        if (!$this->ForomRep->contains($foromRep)) {
            $this->ForomRep->add($foromRep);
            $foromRep->setForum($this);
        }

        return $this;
    }

    public function removeForomRep(ForumResponse $foromRep): static
    {
        if ($this->ForomRep->removeElement($foromRep)) {
            // set the owning side to null (unless already changed)
            if ($foromRep->getForum() === $this) {
                $foromRep->setForum(null);
            }
        }

        return $this;
    }

    public function isApproved(): bool
    {
        return $this->isApproved;
    }

    public function setIsApproved(bool $isApproved): static
    {
        $this->isApproved = $isApproved;

        return $this;
    }

    /**
     * @return Collection<int, Rating>
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function addRating(Rating $rating): static
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings->add($rating);
            $rating->setForum($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): static
    {
        if ($this->ratings->removeElement($rating)) {
            // set the owning side to null (unless already changed)
            if ($rating->getForum() === $this) {
                $rating->setForum(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Report>
     */
    public function getReports(): Collection
    {
        return $this->reports;
    }

    public function addReport(Report $report): static
    {
        if (!$this->reports->contains($report)) {
            $this->reports->add($report);
            $report->setForum($this);
        }

        return $this;
    }

    public function removeReport(Report $report): static
    {
        if ($this->reports->removeElement($report)) {
            // set the owning side to null (unless already changed)
            if ($report->getForum() === $this) {
                $report->setForum(null);
            }
        }

        return $this;
    }

    /**
     * Vérifie si le forum a été signalé 3 fois ou plus.
     */
    public function isReportedThreeTimes(): bool
    {
        return count($this->reports) >= 3;
    }

    /**
     * Vérifie si l'utilisateur a déjà signalé ce forum.
     */
    public function isReportedByUser(User $user): bool
    {
        foreach ($this->reports as $report) {
            if ($report->getReportedBy()->getId() === $user->getId()) {
                return true;
            }
        }
        return false;
    }
}