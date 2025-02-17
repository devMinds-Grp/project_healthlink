<?php

namespace App\Entity;

use App\Repository\BloodDonationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BloodDonationRepository::class)]
class BloodDonation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Description = null;

    #[ORM\Column(length: 255)]
    private ?string $Location = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $Date = null;

    #[ORM\Column(length: 255)]
    private ?string $PhoneNumb = null;

    #[ORM\ManyToOne(inversedBy: 'bloodDonations')]
    private ?User $BldDon = null;

    /**
     * @var Collection<int, DonationResponse>
     */
    #[ORM\OneToMany(targetEntity: DonationResponse::class, mappedBy: 'bloodDonation')]
    private Collection $DldRep;

    public function __construct()
    {
        $this->DldRep = new ArrayCollection();
    }

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

    public function getLocation(): ?string
    {
        return $this->Location;
    }

    public function setLocation(string $Location): static
    {
        $this->Location = $Location;

        return $this;
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

    public function getPhoneNumb(): ?string
    {
        return $this->PhoneNumb;
    }

    public function setPhoneNumb(string $PhoneNumb): static
    {
        $this->PhoneNumb = $PhoneNumb;

        return $this;
    }

    public function getBldDon(): ?User
    {
        return $this->BldDon;
    }

    public function setBldDon(?User $BldDon): static
    {
        $this->BldDon = $BldDon;

        return $this;
    }

    /**
     * @return Collection<int, DonationResponse>
     */
    public function getDldRep(): Collection
    {
        return $this->DldRep;
    }

    public function addDldRep(DonationResponse $dldRep): static
    {
        if (!$this->DldRep->contains($dldRep)) {
            $this->DldRep->add($dldRep);
            $dldRep->setBloodDonation($this);
        }

        return $this;
    }

    public function removeDldRep(DonationResponse $dldRep): static
    {
        if ($this->DldRep->removeElement($dldRep)) {
            // set the owning side to null (unless already changed)
            if ($dldRep->getBloodDonation() === $this) {
                $dldRep->setBloodDonation(null);
            }
        }

        return $this;
    }
}
