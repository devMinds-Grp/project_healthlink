<?php

namespace App\Entity;

use App\Enum\StatutRdv;
use App\Enum\TypeRdv;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "Appointment")]
class Appointment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: "date")]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(enumType: StatutRdv::class)]
    private ?StatutRdv $statut = null;

    #[ORM\Column(enumType: TypeRdv::class)]
    private ?TypeRdv $type = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'appointmentsAsDoctor')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $doctor = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'appointmentsAsPatient')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $patient = null;

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

   
}
