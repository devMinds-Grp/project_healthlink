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
    #[Assert\NotBlank(message: "Email est obligatoire.")]
    #[Assert\Email(message: "Email '{{ value }}' n'est pas valide.")]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le mot de passe est obligatoire.")]
    #[Assert\Length(min: 8, max: 255, minMessage: "Le mot de passe doit contenir au moins {{ limit }} caractères.")]
    #[Assert\Regex(
        pattern: '/^(?=.*[A-Z])(?=.*\d).+$/',
        message: "Le mot de passe doit contenir au moins une majuscule et un chiffre."
    )]
    private ?string $motDePasse = null;

    #[ORM\ManyToOne(targetEntity: Role::class, inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Role $role = null;

    #[ORM\Column(length: 8, nullable: true)]
    #[Assert\Regex(pattern: "/^[0-9]{8}$/", message: "Le numéro de téléphone doit être valide.")]
    private ?string $numTel = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: "Adresse ne peut pas dépasser {{ limit }} caractères.")]
    #[Assert\Regex(
        pattern: '/^[A-Z]/',
        message: "Adresse doit commencer par une lettre majuscule."
    )]
    private ?string $adresse = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Assert\Length(max: 50, maxMessage: "La spécialité ne peut pas dépasser {{ limit }} caractères.")]
    private ?string $speciality = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $categorieSoin = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\File(
        maxSize: "2M",
        mimeTypes: ["image/jpeg", "image/png", "image/gif"],
        mimeTypesMessage: "Veuillez téléverser une image valide (JPEG, PNG, GIF)."
    )]
    private ?string $image = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\File(
        maxSize: "2M",
        mimeTypes: ["image/jpeg", "image/png", "image/gif"],
        mimeTypesMessage: "Veuillez téléverser une image valide (JPEG, PNG, GIF)."
    )]
    private ?string $imageprofile = null;

    #[ORM\Column(type: 'string', length: 20, options: ['default' => 'en attente'])]
    private ?string $statut = 'en attente';
    
    #[ORM\Column(type: 'string', length: 6, nullable: true)]
    private ?string $resetCode = null;

    public function getResetCode(): ?string
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
        $this->notifications = new ArrayCollection();
        return $this->resetCode;
    }

    public function setResetCode(?string $resetCode): self
    {
        $this->resetCode = $resetCode;
        return $this;
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
    // In App\Entity\User

#[ORM\OneToMany(targetEntity: Notification::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
private Collection $notifications;


/**
 * @return Collection|Notification[]
 */
public function getNotifications(): Collection
{
    return $this->notifications;
}

public function addNotification(Notification $notification): self
{
    if (!$this->notifications->contains($notification)) {
        $this->notifications[] = $notification;
        $notification->setUser($this);
    }
    return $this;
}

public function removeNotification(Notification $notification): self
{
    if ($this->notifications->removeElement($notification)) {
        if ($notification->getUser() === $this) {
            $notification->setUser(null);
        }
    }
    return $this;
}

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $bannedUntil = null;

    /**
     * @var Collection<int, Rating>
     */
    #[ORM\OneToMany(targetEntity: Rating::class, mappedBy: 'user')]
    private Collection $ratings;
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
        $this->ratings = new ArrayCollection();
    }

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
        // Initialiser avec ROLE_USER par défaut
        $roles = ['ROLE_USER'];

        // Vérifier si l'utilisateur a un rôle associé
        $role = $this->getRole();

        // Si le rôle existe et a un nom, l'ajouter au tableau des rôles
        if ($role !== null && $role->getNom() !== null) {
            // Normaliser le nom du rôle (supprimer les accents et convertir en majuscules)
            $roleName = strtoupper($role->getNom());
            $roles[] = 'ROLE_' . $roleName;
        }

        return $roles;
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;
        return $this;
    }

    public function getImageprofile(): ?string
    {
        return $this->imageprofile;
    }

    public function setImageprofile(?string $imageprofile): self
    {
        $this->imageprofile = $imageprofile;
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

    public function getBannedUntil(): ?\DateTimeInterface
    {
        return $this->bannedUntil;
    }

    public function setBannedUntil(?\DateTimeInterface $bannedUntil): self
    {
        $this->bannedUntil = $bannedUntil;
        return $this;
    }

    /**
     * Vérifie si l'utilisateur est actuellement banni.
     */
    public function isBanned(): bool
    {
        return $this->bannedUntil && $this->bannedUntil > new \DateTime();
    }

    /**
     * @return Collection<int, Rating>
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function addRating(Rating $rating): static
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings->add($rating);
            $rating->setUser($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): static
    {
        if ($this->ratings->removeElement($rating)) {
            // set the owning side to null (unless already changed)
            if ($rating->getUser() === $this) {
                $rating->setUser(null);
            }
        }

        return $this;
    }
}