<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=DonneurRepository::class)
 */
class Donneur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="Le nom ne peut pas être vide.")
     * @Assert\Length(min=3, max=100, minMessage="Le nom doit comporter au moins {{ limit }} caractères.", maxMessage="Le nom ne peut pas dépasser {{ limit }} caractères.")
     */
    private $nom;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="L'âge ne peut pas être vide.")
     * @Assert\Range(min=18, max=100, minMessage="L'âge doit être d'au moins {{ limit }} ans.", maxMessage="L'âge ne peut pas dépasser {{ limit }} ans.")
     */
    private $age;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="Le poids ne peut pas être vide.")
     * @Assert\Positive(message="Le poids doit être un nombre positif.")
     */
    private $poids;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotNull(message="L'état de santé ne peut pas être vide.")
     */
    private $estEnBonneSante;

    // Getters and Setters...

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getPoids(): ?float
    {
        return $this->poids;
    }

    public function setPoids(float $poids): self
    {
        $this->poids = $poids;

        return $this;
    }

    public function getEstEnBonneSante(): ?bool
    {
        return $this->estEnBonneSante;
    }

    public function setEstEnBonneSante(bool $estEnBonneSante): self
    {
        $this->estEnBonneSante = $estEnBonneSante;

        return $this;
    }
}
