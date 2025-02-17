<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity]
#[ORM\Table(name: "utilisateur")]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom ne peut pas être vide.")]
    #[Assert\Length(min: 2, max: 50, minMessage: "Le nom doit avoir au moins {{ limit }} caractères.", maxMessage: "Le nom ne peut pas dépasser {{ limit }} caractères.")]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le prénom ne peut pas être vide.")]
    #[Assert\Length(min: 2, max: 50, minMessage: "Le prénom doit avoir au moins {{ limit }} caractères.", maxMessage: "Le prénom ne peut pas dépasser {{ limit }} caractères.")]
    private ?string $prenom = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(message: "L'email est obligatoire.")]
    #[Assert\Email(message: "L'email '{{ value }}' n'est pas valide.")]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le mot de passe est obligatoire.")]
    #[Assert\Length(min: 8, max: 255, minMessage: "Le mot de passe doit contenir au moins {{ limit }} caractères.")]
    private ?string $motDePasse = null;

    #[ORM\ManyToOne(targetEntity: Role::class, inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Role $role = null;

    #[ORM\Column(length: 8, nullable: true)]
    #[Assert\Regex(pattern: "/^(\+?\d{1,3})?\d{8,14}$/", message: "Le numéro de téléphone doit être valide.")]
    private ?string $numTel = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: "L'adresse ne peut pas dépasser {{ limit }} caractères.")]
    private ?string $adresse = null;


    #[ORM\Column(length: 50, nullable: true)]
    #[Assert\Length(max: 50, maxMessage: "La spécialité ne peut pas dépasser {{ limit }} caractères.")]
    private ?string $speciality = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $categorieSoin = null;

    #[ORM\Column(length: 255, nullable: true)]

    private ?string $image = null;

    #[ORM\Column(type: 'string', length: 20, options: ['default' => 'en attente'])]
    private ?string $statut = 'en attente';


    public function __construct()
    {
        $this->UserForum = new ArrayCollection();
        $this->caresAsCaregiver = new ArrayCollection();
        $this->caresAsPatient = new ArrayCollection();
        $this->diagnostics = new ArrayCollection();
        $this->bloodDonations = new ArrayCollection();
        $this->pharmacies = new ArrayCollection();
        $this->CargiverCare = new ArrayCollection();
        $this->Prescriptions = new ArrayCollection();
        $this->appointmentsAsDoctor = new ArrayCollection();
        $this->appointmentsAsPatient = new ArrayCollection();
        $this->reclamations = new ArrayCollection();
        $this->handledReclamations = new ArrayCollection();
    }

    #[ORM\OneToMany(targetEntity: Care::class, mappedBy: 'caregiver')]
    private Collection $caresAsCaregiver;

    #[ORM\OneToMany(targetEntity: Care::class, mappedBy: 'patient')]
    private Collection $caresAsPatient;

    /**
     * @var Collection<int, Diagnostic>
     */
    #[ORM\OneToMany(targetEntity: Diagnostic::class, mappedBy: 'DiagPatient')]
    private Collection $diagnostics;

    /**
     * @var Collection<int, BloodDonation>
     */
    #[ORM\OneToMany(targetEntity: BloodDonation::class, mappedBy: 'BldDon')]
    private Collection $bloodDonations;

    /**
     * @var Collection<int, Pharmacy>
     */
    #[ORM\OneToMany(targetEntity: Pharmacy::class, mappedBy: 'PharPatient')]
    private Collection $pharmacies;

    #[ORM\OneToMany(targetEntity: Care::class, mappedBy: 'caregiver')]
    private Collection $CargiverCare;

    #[ORM\ManyToOne(inversedBy: 'CareResponse')]
    private ?CareResponse $careResponse = null;

    /**
     * @var Collection<int, Prescription>
     */
    #[ORM\OneToMany(targetEntity: Prescription::class, mappedBy: 'PrescriptionDoctor')]
    private Collection $Prescriptions;

    #[ORM\OneToMany(targetEntity: Appointment::class, mappedBy: 'doctor')]
    private Collection $appointmentsAsDoctor;

    #[ORM\OneToMany(targetEntity: Appointment::class, mappedBy: 'patient')]
    private Collection $appointmentsAsPatient;

    #[ORM\OneToMany(targetEntity: Reclamation::class, mappedBy: 'patient')]
    private Collection $reclamations;

    #[ORM\OneToMany(targetEntity: Reclamation::class, mappedBy: 'admin')]
    private Collection $handledReclamations;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;
        return $this;
    }
    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }
    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getMotDePasse(): ?string
    {
        return $this->motDePasse;
    }

    public function setMotDePasse(string $motDePasse): self
    {
        $this->motDePasse = $motDePasse;
        return $this;
    }
    public function getPassword(): ?string
    {
        return $this->motDePasse;
    }
    public function getRoles(): array
    {
        // Récupérer le rôle de l'utilisateur
        $role = $this->getRole();

        // Si aucun rôle n'est défini, retourner un rôle par défaut (par exemple, ROLE_USER)
        if (!$role) {
            return ['ROLE_USER'];
        }

        // Retourner le rôle sous forme de tableau
        return ['ROLE_' . strtoupper($role->getNom())];
    }
    public function eraseCredentials(): void
    {
        // Cette méthode est obligatoire mais peut rester vide
    }

    public function getUserIdentifier(): string
    {
        return $this->email; // Symfony utilise cette méthode pour l'authentification
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;
        return $this;
    }

    public function getNumTel(): ?string
    {
        return $this->numTel;
    }

    public function setNumTel(?string $numTel): self
    {
        $this->numTel = $numTel;
        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;
        return $this;
    }

    public function getSpeciality(): ?string
    {
        return $this->speciality;
    }

    public function setSpeciality(?string $speciality): self
    {
        $this->speciality = $speciality;
        return $this;
    }

    public function getCategorieSoin(): ?string
    {
        return $this->categorieSoin;
    }

    public function setCategorieSoin(?string $categorieSoin): self
    {
        $this->categorieSoin = $categorieSoin;
        return $this;
    }
}