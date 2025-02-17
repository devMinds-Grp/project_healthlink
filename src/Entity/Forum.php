<?php

namespace App\Entity;

use App\Repository\ForumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ForumRepository::class)]
class Forum
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Title = null;

    #[ORM\Column(length: 255)]
    private ?string $Description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $Date = null;

    #[ORM\ManyToOne(inversedBy: 'UserForum')]
    private ?User $user = null;

    /**
     * @var Collection<int, ForumResponse>
     */
    #[ORM\OneToMany(targetEntity: ForumResponse::class, mappedBy: 'forum')]
    private Collection $ForomRep;

    public function __construct()
    {
        $this->ForomRep = new ArrayCollection();
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
}
