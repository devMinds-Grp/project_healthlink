<?php

namespace App\Entity;

use App\Enum\ResultatDiagnostic;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Diagnostic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $fichier;

    #[ORM\Column(enumType: ResultatDiagnostic::class)]
    private ResultatDiagnostic $resultat;

    #[ORM\ManyToOne(inversedBy: 'diagnostics')]
    private ?User $DiagUser = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFichier(): string
    {
        return $this->fichier;
    }

    public function setFichier(string $fichier): self
    {
        $this->fichier = $fichier;
        return $this;
    }

    public function getResultat(): ResultatDiagnostic
    {
        return $this->resultat;
    }

    public function setResultat(ResultatDiagnostic $resultat): self
    {
        $this->resultat = $resultat;
        return $this;
    }

    public function getDiagUser(): ?User
    {
        return $this->DiagUser;
    }

    public function setDiagUser(?User $DiagUser): static
    {
        $this->DiagUser = $DiagUser;

        return $this;
    }

}
