<?php

namespace App\Entity;

use App\Repository\CareRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: CareRepository::class)]
class Care
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $Date = null;

    #[ORM\Column(length: 255)]
    private ?string $Address = null;

    #[ORM\Column(length: 255)]
    private ?string $Description = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'caresAsCaregiver')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $caregiver = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'caresAsPatient')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $patient = null;

    #[ORM\OneToMany(mappedBy: 'care', targetEntity: CareResponse::class, cascade: ['persist', 'remove'])]
    private Collection $careResponse;

    #[ORM\ManyToOne(inversedBy: 'cares')]
    private ?User $CareUser = null;

    public function __construct()
    {
        $this->careResponse = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): static
    {
        $this->Date = $Date;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->Address;
    }

    public function setAddress(string $Address): static
    {
        $this->Address = $Address;

        return $this;
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



    public function getcareResponse(): ?CareResponse
    {
        return $this->careResponse;
    }

    public function setcareResponse(?CareResponse $careResponse): static
    {
        $this->careResponse = $careResponse;

        return $this;
    }

    public function getCareUser(): ?User
    {
        return $this->CareUser;
    }

    public function setCareUser(?User $CareUser): static
    {
        $this->CareUser = $CareUser;

        return $this;
    }
}
