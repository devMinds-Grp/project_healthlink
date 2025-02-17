<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
<<<<<<< HEAD
use Symfony\Component\Validator\Constraints as Assert;
 
=======

>>>>>>> master
#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
<<<<<<< HEAD
    #[Assert\NotBlank(message: "champ ne peut pas être vide")]
    #[Assert\NotNull(message: "Le titre ne peut pas être null")]
    private ?string $nom = null;
=======
    private ?string $Name = null;
>>>>>>> master

    /**
     * @var Collection<int, Reclamation>
     */
<<<<<<< HEAD
    #[ORM\OneToMany(targetEntity: Reclamation::class, mappedBy: 'category')]
=======
    #[ORM\OneToMany(targetEntity: Reclamation::class, mappedBy: 'RecCat')]
>>>>>>> master
    private Collection $reclamations;

    public function __construct()
    {
        $this->reclamations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

<<<<<<< HEAD
    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
=======
    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): static
    {
        $this->Name = $Name;
>>>>>>> master

        return $this;
    }

    /**
     * @return Collection<int, Reclamation>
     */
    public function getReclamations(): Collection
    {
        return $this->reclamations;
    }

    public function addReclamation(Reclamation $reclamation): static
    {
        if (!$this->reclamations->contains($reclamation)) {
            $this->reclamations->add($reclamation);
<<<<<<< HEAD
            $reclamation->setCategory($this);
=======
            $reclamation->setRecCat($this);
>>>>>>> master
        }

        return $this;
    }

    public function removeReclamation(Reclamation $reclamation): static
    {
        if ($this->reclamations->removeElement($reclamation)) {
            // set the owning side to null (unless already changed)
<<<<<<< HEAD
            if ($reclamation->getCategory() === $this) {
                $reclamation->setCategory(null);
=======
            if ($reclamation->getRecCat() === $this) {
                $reclamation->setRecCat(null);
>>>>>>> master
            }
        }

        return $this;
    }
}
