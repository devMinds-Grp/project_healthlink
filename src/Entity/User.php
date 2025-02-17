<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: "User")]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $motDePasse = null;

    #[ORM\ManyToOne(targetEntity: Role::class, inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Role $role = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $numTel = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $speciality = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $categorieSoin = null;

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