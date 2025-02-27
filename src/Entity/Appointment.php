<?php

namespace App\Entity;

use App\Enum\StatutRdv;
use App\Enum\TypeRdv;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\AppointmentRepository;

#[ORM\Entity(repositoryClass: AppointmentRepository::class)]
#[ORM\Table(name: "Appointment")]
class Appointment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: "date")]
    #[Assert\NotBlank(message: "La date est obligatoire.")]
    #[Assert\GreaterThan(
        value: "today",
        message: "La date doit être postérieure à aujourd'hui"
    )]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(enumType: StatutRdv::class, nullable: false)]
    private ?StatutRdv $statut = null; 

    #[ORM\Column(enumType: TypeRdv::class)]
    #[Assert\NotBlank(message: "Le type de rendez-vous est obligatoire.")]
    private ?TypeRdv $type = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'appointmentsAsDoctor')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $doctor = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'appointmentsAsPatient')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $patient = null;

    #[ORM\OneToMany(mappedBy: 'RDVCard', targetEntity: Prescription::class, cascade: ['remove'])]
    private Collection $prescriptions;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getStatut(): ?StatutRdv
    {
        return $this->statut;
    }

    public function setStatut(StatutRdv $statut): self
    {
        $this->statut = $statut;
        return $this;
    }

    public function getType(): ?TypeRdv
    {
        return $this->type;
    }

    public function setType(TypeRdv $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getDoctor(): ?User
    {
        return $this->doctor;
    }

    public function setDoctor(?User $doctor): self
    {
        $this->doctor = $doctor;
        return $this;
    }

    public function getPatient(): ?User
    {
        return $this->patient;
    }

    public function setPatient(?User $patient): self
    {
        $this->patient = $patient;
        return $this;
    }

    public function getMedecin(): ?User
    {
        return $this->medecin;
    }

    public function setMedecin(?User $medecin): static
    {
        $this->medecin = $medecin;
        return $this;
    }
    
    public function __toString(): string
{
    return $this->date ? $this->date->format('Y-m-d H:i') : '';
}

// src/Entity/Appointment.php
public function __construct()
{
    $this->prescriptions = new ArrayCollection();
    $this->statut = StatutRdv::EN_ATTENTE; // Valeur par défaut
}

public function getPrescriptions(): Collection
{
    return $this->prescriptions;
}

public function addPrescription(Prescription $prescription): self
{
    if (!$this->prescriptions->contains($prescription)) {
        $this->prescriptions->add($prescription);
        $prescription->setRDVCard($this);
    }
    return $this;
}

public function removePrescription(Prescription $prescription): self
{
    if ($this->prescriptions->removeElement($prescription)) {
        if ($prescription->getRDVCard() === $this) {
            $prescription->setRDVCard(null);
        }
    }
    return $this;
}

}
