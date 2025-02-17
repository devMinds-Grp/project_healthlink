<?php

namespace App\Entity;

use App\Enum\StatutReclamation;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $titre;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column(enumType: StatutReclamation::class)]
    private StatutReclamation $statutReclamation;

    #[ORM\ManyToOne(inversedBy: 'reclamations')]
    private ?Category $RecCat = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reclamations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $patient = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'handledReclamations')]
    private ?User $admin = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getStatutReclamation(): StatutReclamation
    {
        return $this->statutReclamation;
    }

    public function setStatutReclamation(StatutReclamation $statutReclamation): self
    {
        $this->statutReclamation = $statutReclamation;
        return $this;
    }

    public function getRecCat(): ?Category
    {
        return $this->RecCat;
    }

    public function setRecCat(?Category $RecCat): static
    {
        $this->RecCat = $RecCat;

        return $this;
    }
}
