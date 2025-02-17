<?php

namespace App\Entity;

use App\Repository\CareResponseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert; // Add this line

#[ORM\Entity(repositoryClass: CareResponseRepository::class)]
class CareResponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_rep = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(
        min: 5,
        minMessage: 'The response content must be at least {{ limit }} characters long.',
    )]
    private ?string $contenu_rep = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Care::class, inversedBy: 'careResponse')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Care $care = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateRep(): ?\DateTimeInterface
    {
        return $this->date_rep;
    }

    public function setDateRep(\DateTimeInterface $date_rep): static
    {
        $this->date_rep = $date_rep;
        return $this;
    }

    public function __construct()
    {
        $this->date_rep = new \DateTime('now', new \DateTimeZone('Africa/Tunis'));
    }

    public function getContenuRep(): ?string
    {
        return $this->contenu_rep;
    }

    public function setContenuRep(?string $contenu_rep): static
    {
        $this->contenu_rep = $contenu_rep;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function getCare(): ?Care
    {
        return $this->care;
    }

    public function setCare(?Care $care): static
    {
        $this->care = $care;
        return $this;
    }
}
