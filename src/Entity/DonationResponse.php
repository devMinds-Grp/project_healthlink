<?php

namespace App\Entity;

use App\Repository\DonationResponseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DonationResponseRepository::class)]
class DonationResponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Description = null;

    #[ORM\ManyToOne(inversedBy: 'DldRep')]
    private ?BloodDonation $bloodDonation = null;

    public function getId(): ?int
    {
        return $this->id;
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
