<?php

namespace App\Entity;

use App\Enum\TypePharmacie;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Pharmacy
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $nom;

    #[ORM\Column(type: 'string', length: 255)]
    private string $adresse;

    #[ORM\Column(type: 'string', length: 20)]
    private string $numTel;

    #[ORM\Column(enumType: TypePharmacie::class)]
    private TypePharmacie $typePharmacie;

    #[ORM\Column(type: 'string', length: 255)]
    private string $horaires;

    #[ORM\ManyToOne(inversedBy: 'pharmacies')]
    private ?User $PharUser = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getAdresse(): string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;
        return $this;
    }

    public function getNumTel(): string
    {
        return $this->numTel;
    }

    public function setNumTel(string $numTel): self
    {
        $this->numTel = $numTel;
        return $this;
    }

    public function getTypePharmacie(): TypePharmacie
    {
        return $this->typePharmacie;
    }

    public function setTypePharmacie(TypePharmacie $typePharmacie): self
    {
        $this->typePharmacie = $typePharmacie;
        return $this;
    }

    public function getHoraires(): string
    {
        return $this->horaires;
    }

    public function setHoraires(string $horaires): self
    {
        $this->horaires = $horaires;
        return $this;
    }

    public function getPharUser(): ?User
    {
        return $this->PharUser;
    }

    public function setPharUser(?User $PharUser): static
    {
        $this->PharUser = $PharUser;

        return $this;
    }
}
