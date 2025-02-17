<?php

namespace App\Entity;

<<<<<<< HEAD
use App\Enum\PatientStatus;
use App\Enum\Status;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
=======
use App\Enum\StatutReclamation;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
>>>>>>> master
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

<<<<<<< HEAD
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le titre ne peut pas être vide")]
    #[Assert\NotNull(message: "Le titre ne peut pas être null")]

    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le description ne peut pas être vide")]

    #[Assert\Length(
        min: 5,
        max: 100,
        minMessage: " description doit comporter au moins {{ limit }} caractères",
        maxMessage: " description ne peut pas dépasser {{ limit }} caractères"
    )]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'reclamations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: "champ ne peut pas être vide")]

    private ?Category $category = null;

    #[ORM\Column(enumType: Status::class)]
    private ?Status $Statut = null;

    #[ORM\Column(length: 255 , nullable:true)]
    
    private ?string $image = null;
=======
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
>>>>>>> master

    public function getId(): ?int
    {
        return $this->id;
    }

<<<<<<< HEAD
    public function getTitre(): ?string
=======
    public function getTitre(): string
>>>>>>> master
    {
        return $this->titre;
    }

<<<<<<< HEAD
    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
=======
    public function setTitre(string $titre): self
    {
        $this->titre = $titre;
        return $this;
    }

    public function getDescription(): string
>>>>>>> master
    {
        return $this->description;
    }

<<<<<<< HEAD
    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getStatut(): ?Status
    {
        return $this->Statut;
    }

    public function setStatut(Status $Statut): static
    {
        $this->Statut = $Statut;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;
=======
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
>>>>>>> master

        return $this;
    }
}
