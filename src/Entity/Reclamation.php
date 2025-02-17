<?php

namespace App\Entity;

use App\Enum\PatientStatus;
use App\Enum\Status;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

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

        return $this;
    }
}
