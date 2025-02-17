<?php

namespace App\Entity;

use App\Repository\CareResponseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: CareResponseRepository::class)]
class CareResponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_rep = null;

    #[ORM\Column(length: 255)]
    private ?string $contenu_rep = null;


    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'User')]
    private Collection $User ;

    #[ORM\ManyToOne(targetEntity: Care::class, inversedBy: 'careResponse')]
    private ?Care $care = null;

    public function __construct()
    {
        $this->CargiverCare = new ArrayCollection();
    }

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

    public function getContenuRep(): ?string
    {
        return $this->contenu_rep;
    }

    public function setContenuRep(string $contenu_rep): static
    {
        $this->contenu_rep = $contenu_rep;

        return $this;
    }
}
