<?php
namespace App\Entity;

use App\Repository\PrescriptionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: PrescriptionRepository::class)]
class Prescription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom du médicament est obligatoire.")]
    #[Assert\Length(
        min: 5,
        minMessage: "Le nom du médicament doit avoir au moins {{ limit }} caractères."
    )]
    private ?string $nomMedicament = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le dosage est obligatoire.")]
    #[Assert\Length(
        min: 5,
        minMessage: "Le dosage doit avoir au moins {{ limit }} caractères."
    )]
    private ?string $dosage = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La durée est obligatoire.")]
    #[Assert\Length(
        min: 5,
        minMessage: "La durée doit avoir au moins {{ limit }} caractères."
    )]
    private ?string $duree = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "Les notes sont obligatoires.")]
    #[Assert\Length(
        min: 15,
        minMessage: "Les notes doivent avoir au moins {{ limit }} caractères."
    )]
    private ?string $notes = null;

    #[ORM\ManyToOne(inversedBy: 'cards')]
    #[ORM\JoinColumn(nullable: true)] // Mettre false si CardUser est obligatoire
    private ?User $cardUser = null;

    #[ORM\ManyToOne(targetEntity: Appointment::class, inversedBy: 'prescriptions')]
    #[ORM\JoinColumn(name: "RDVCard_id", referencedColumnName: "id", nullable: false)]
    private ?Appointment $RDVCard = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomMedicament(): ?string
    {
        return $this->nomMedicament;
    }

    public function setNomMedicament(string $nomMedicament): static
    {
        $this->nomMedicament = $nomMedicament;
        return $this;
    }

    public function getDosage(): ?string
    {
        return $this->dosage;
    }

    public function setDosage(string $dosage): static
    {
        $this->dosage = $dosage;
        return $this;
    }

    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree(string $duree): static
    {
        $this->duree = $duree;
        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(string $notes): static
    {
        $this->notes = $notes;
        return $this;
    }

    public function getRDVCard(): ?Appointment
    {
        return $this->RDVCard;
    }

    public function setRDVCard(?Appointment $RDVCard): static
    {
        $this->RDVCard = $RDVCard;
        return $this;
    }
}
