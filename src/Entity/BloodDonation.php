<?php

namespace App\Entity;

use App\Repository\BloodDonationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: BloodDonationRepository::class)]
class BloodDonation
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
    private ?string $description = null;  // Allow null value

        #[ORM\Column(length: 255)]
        #[Assert\NotBlank(message: 'Le lieu est obligatoire.')]
        #[Assert\Length(
            min: 3,
            max: 255,
            minMessage: 'Le lieu doit contenir au moins {{ limit }} caractères.',
            maxMessage: 'Le lieu ne peut pas dépasser {{ limit }} caractères.'
        )]

    private ?string $lieu = null;

    #[ORM\Column(type: 'date', nullable: true)]
    #[Assert\NotBlank(message: "La date est obligatoire.")]
    #[Assert\GreaterThan(
        value: "today",
        message: "La date doit être postérieure à aujourd'hui"
    )]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message: 'Le numéro de téléphone est obligatoire.')]
    #[Assert\Length(
        min: 8,
        max: 20,
        minMessage: 'Le numéro de téléphone doit contenir au moins {{ limit }} caractères.',
        maxMessage: 'Le numéro de téléphone ne peut pas dépasser {{ limit }} caractères.'
    )]
    #[Assert\Regex(
        pattern: '/^\d+$/',
        message: 'Le numéro de téléphone ne doit contenir que des chiffres.'
    )]
    
    private ?string $numTel = null;

    #[ORM\ManyToOne(inversedBy: 'bloodDonations')]
    private ?User $BldDon = null;

    #[ORM\OneToMany(targetEntity: DonationResponse::class, mappedBy: 'bloodDonation', cascade: ['remove'])]
    private Collection $DldRep;


    public function __construct()
    {
        $this->DldRep = new ArrayCollection();
        $this->date = new \DateTime(); 
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getdescription(): ?string
    {
        return $this->description;
    }

    public function setdescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getlieu(): ?string
    {
        return $this->lieu;
    }

    public function setlieu(?string $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
{
    return $this->date;
}

public function setDate(?\DateTimeInterface $date): static
{
    $this->date = $date;

    return $this;
}


    public function getnumTel(): ?string
    {
        return $this->numTel;
    }

    public function setnumTel(?string $numTel): static
    {
        $this->numTel = $numTel;

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
            if ($dldRep->getBloodDonation() === $this) {
                $dldRep->setBloodDonation(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->lieu; 
    }
    

}
