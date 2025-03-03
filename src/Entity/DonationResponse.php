<?php

namespace App\Entity;

use App\Repository\DonationResponseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DonationResponseRepository::class)]
class DonationResponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'La description est obligatoire.')]
    #[Assert\Length(
        min: 5,
        max: 255,
        minMessage: 'La description doit contenir au moins {{ limit }} caractères.',
        maxMessage: 'La description ne peut pas dépasser {{ limit }} caractères.'
    )]

    private ?string $description = null;

    #[ORM\ManyToOne(targetEntity: BloodDonation::class, inversedBy: 'DldRep')]
    private ?BloodDonation $bloodDonation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getdescription(): ?string
    {
        return $this->description;
    }

    public function setdescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getBloodDonation(): ?BloodDonation
    {
        return $this->bloodDonation;
    }

    public function setBloodDonation(?BloodDonation $bloodDonation): static
    {
        $this->bloodDonation = $bloodDonation;

        return $this;
    }
}
