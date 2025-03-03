<?php

namespace App\Controller;

use App\Entity\User;
use Imagine\Gd\Imagine;
use Imagine\Gd\Font;
use Imagine\Image\Box;
use Imagine\Image\Point;
use App\Form\PatientType;
use App\Form\MedecinType;
use App\Form\SoignantType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Role;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use App\Repository\UserRepository;

#[Route('/user')]
final class UserController extends AbstractController
{
    #[Route(name: 'app_user_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager
            ->getRepository(User::class)
            ->findAll();

        return $this->render('user/showattente.html.twig', [
            'users' => $users,
        ]);
    }


    #[Route('/patients', name: 'app_user_patients', methods: ['GET'])]
    public function patients(EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'objet Role correspondant au rôle "Patient"
        $rolePatient = $entityManager
            ->getRepository(Role::class)
            ->findOneBy(['nom' => 'Patient']);

        // Si le rôle "Patient" n'existe pas, retourner une liste vide
        if (!$rolePatient) {
            return $this->render('user/patients.html.twig', [
                'patients' => [],
            ]);
        }

        // Récupérer les utilisateurs ayant ce rôle
        $patients = $entityManager
            ->getRepository(User::class)
            ->findBy(['role' => $rolePatient]);

        return $this->render('user/patients.html.twig', [
            'patients' => $patients,
        ]);
    }

    #[Route('/medecins', name: 'app_user_medecins', methods: ['GET'])]
    public function medecins(EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'objet Role correspondant au rôle "Médecin"
        $roleMedecin = $entityManager
            ->getRepository(Role::class)
            ->findOneBy(['nom' => 'Médecin']);

        // Si le rôle "Médecin" n'existe pas, retourner une liste vide
        if (!$roleMedecin) {
            return $this->render('user/medecins.html.twig', [
                'medecins' => [],
                'specialities' => [],
                'cities' => []
            ]);
        }

        // Récupérer les utilisateurs ayant ce rôle
        $medecins = $entityManager
            ->getRepository(User::class)
            ->findBy(['role' => $roleMedecin]);

        // Extraire les spécialités uniques
        // $specialities = array_unique(array_filter(array_map(fn($m) => $m->getSpeciality(), $medecins)));
        // $addresses = array_unique(array_map(function ($doctor) {
        //     return $doctor->getAdresse(); }, $doctors));

        return $this->render('user/medecins.html.twig', [
            'medecins' => $medecins,
            // 'specialities' => $specialities,
            // 'addresses' => $addresses,
        ]);
    }

    #[Route('/soignants', name: 'app_user_soignants', methods: ['GET'])]
    public function soignants(EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'objet Role correspondant au rôle "Soignant"
        $roleSoignant = $entityManager
            ->getRepository(Role::class)
            ->findOneBy(['nom' => 'Soignant']);

        // Si le rôle "Soignant" n'existe pas, retourner une liste vide
        if (!$roleSoignant) {
            return $this->render('user/soignants.html.twig', [
                'soignants' => [],
            ]);
        }

        // Récupérer les utilisateurs ayant ce rôle
        $soignants = $entityManager
            ->getRepository(User::class)
            ->findBy(['role' => $roleSoignant]);

        return $this->render('user/soignants.html.twig', [
            'soignants' => $soignants,
        ]);
    }

    #[Route(path: '/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, MailerInterface $mailer): Response
    {
        $user = new User();

        // Récupérer le rôle depuis l'URL
        $roleName = $request->query->get('role', 'Patient'); // Par défaut : Patient

        // Chercher le rôle dans la base de données
        $role = $entityManager->getRepository(Role::class)->findOneBy(['nom' => $roleName]);

        if (!$role) {
            throw $this->createNotFoundException("Le rôle '$roleName' n'existe pas.");
        }

        $user->setRole($role); // Affecter le rôle à l'utilisateur

        if ($roleName === 'Médecin') {
            $form = $this->createForm(MedecinType::class, $user);
        } elseif ($roleName === 'Soignant') {
            $form = $this->createForm(SoignantType::class, $user);
        } else {
            $form = $this->createForm(PatientType::class, $user);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($roleName === 'Médecin' || $roleName === 'Soignant') {
                $imageFile = $form->get('image')->getData(); // Récupérer l'image

                if ($imageFile) {
                    $uploadsDir = $this->getParameter('kernel.project_dir') . '/public/uploads';
                    $newFilename = uniqid() . '.' . $imageFile->guessExtension(); // Générer un nom unique
                    $imageFile->move($uploadsDir, $newFilename); // Déplacer l'image dans le dossier

                    $user->setImage($newFilename); // Enregistrer le nom du fichier dans l'entité
                }
            }

            $imageFile = $form->get('imageprofile')->getData(); // Récupérer l'image
            if ($imageFile) {
                $uploadsDir = $this->getParameter('kernel.project_dir') . '/public/uploads';
                $newFilename = uniqid() . '.' . $imageFile->guessExtension(); // Générer un nom unique
                $imageFile->move($uploadsDir, $newFilename); // Déplacer l'image dans le dossier

                $user->setImageProfile($newFilename);// Enregistrer le nom du fichier dans l'entité
            } else {
                // Générer une image de profil par défaut avec initiales
                $initials = strtoupper(substr($user->getNom(), 0, 1) . substr($user->getPrenom(), 0, 1));
                $defaultAvatar = $this->generateInitialAvatar($initials);
                $user->setImageProfile($defaultAvatar);
            }

            $nom = ucfirst(strtolower($user->getNom())); // Mettre la première lettre en majuscule
            $plainPassword = $nom . '.123'; // Construire le mot de passe pour l'affichage dans l'email
            // Encoder le mot de passe
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getMotDePasse());
            $user->setMotDePasse($hashedPassword);

            $user->setStatut('approuvé');
            $entityManager->persist($user);
            $entityManager->flush();
            // Générer le mot de passe visible dans l'email

            $email = (new TemplatedEmail())
                ->from('amenichakroun62@gmail.com') // Votre adresse email
                ->to($user->getEmail()) // Utiliser l'email de l'utilisateur
                ->subject('Bienvenue sur notre plateforme')
                ->htmlTemplate('emails/registration.html.twig')
                ->context([
                    'user' => $user,
                    'motDePasseAffiche' => $plainPassword,
                ]);
            $mailer->send($email);
            // Redirection selon le rôle
            if ($roleName === 'Médecin') {
                return $this->redirectToRoute('app_user_medecins', [], Response::HTTP_SEE_OTHER);
            } elseif ($roleName === 'Soignant') {
                return $this->redirectToRoute('app_user_soignants', [], Response::HTTP_SEE_OTHER);
            } else {
                return $this->redirectToRoute('app_user_patients', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'role' => $roleName,

        ]);
    }

    private function generateInitialAvatar(string $initials): string
    {
        // Taille de l'image
        $width = 100;
        $height = 100;

        // Créer une image vide
        $image = imagecreatetruecolor($width, $height);

        // Activer l'antialiasing pour un rendu plus lisse
        imageantialias($image, true);

        // Générer une couleur de fond dynamique
        $hash = crc32($initials);
        $red = ($hash & 0xFF0000) >> 16;
        $green = ($hash & 0x00FF00) >> 8;
        $blue = $hash & 0x0000FF;
        $backgroundColor = imagecolorallocate($image, $red, $green, $blue);

        // Remplir l'image avec la couleur de fond
        imagefilledrectangle($image, 0, 0, $width, $height, $backgroundColor);

        // Couleur du texte (blanc)
        $textColor = imagecolorallocate($image, 255, 255, 255);

        // Chemin vers une police TTF (remplacez par le chemin de votre police)
        $fontPath = $this->getParameter('kernel.project_dir') . '/public/fonts/Roboto-Regular.ttf';

        // Taille de la police (ajustez selon vos préférences)
        $fontSize = 40;

        // Calculer la position du texte pour le centrer
        $textBox = imagettfbbox($fontSize, 0, $fontPath, $initials);
        $textWidth = $textBox[2] - $textBox[0];
        $textHeight = abs($textBox[7] - $textBox[1]); // Prendre la valeur absolue

        $textX = ($width - $textWidth) / 2;
        $textY = ($height + $textHeight) / 2 - ($textBox[1]); // Ajustement pour un centrage correct

        // Ajouter le texte à l'image
        imagettftext($image, $fontSize, 0, $textX, $textY, $textColor, $fontPath, $initials);

        // Créer un masque circulaire
        $mask = imagecreatetruecolor($width, $height);
        $transparent = imagecolorallocate($mask, 0, 0, 0);
        imagecolortransparent($mask, $transparent);
        imagefilledellipse($mask, $width / 2, $height / 2, $width, $height, $transparent);

        // Appliquer le masque circulaire
        imagecopymerge($image, $mask, 0, 0, 0, 0, $width, $height, 100);
        imagedestroy($mask);

        // Sauvegarder l'image dans un fichier temporaire
        $uploadsDir = $this->getParameter('kernel.project_dir') . '/public/uploads';
        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0777, true);
        }
        $filename = uniqid() . '.png';
        $filePath = $uploadsDir . '/' . $filename;
        imagepng($image, $filePath);

        // Libérer la mémoire
        imagedestroy($image);

        return $filename;
    }
    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
            'role' => $user->getRole()->getNom(), // Envoie le rôle à la vue
        ]);
    }


    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        // Vérifier si le statut est "en attente" et le modifier en "approuvé"
        if ($user->getStatut() === 'en attente') {
            $user->setStatut('approuvé');
            $entityManager->flush();

            // Envoyer un email à l'utilisateur avec un template Twig
            $email = (new TemplatedEmail())
                ->from('amenichakroun62@gmail.com') // Remplacez par l'email de l'administrateur
                ->to($user->getEmail()) // Utiliser l'email de l'utilisateur
                ->subject('Approvation du compte')
                ->htmlTemplate('emails/account_approved.html.twig') // Template Twig pour l'email
                ->context([
                    'user' => $user, // Passer l'utilisateur au template
                ]);

            $mailer->send($email);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        // Choisir dynamiquement le formulaire en fonction du rôle de l'utilisateur
        if ($user->getRole()->getNom() === 'Médecin') {
            $form = $this->createForm(MedecinType::class, $user);
        } elseif ($user->getRole()->getNom() === 'Soignant') {
            $form = $this->createForm(SoignantType::class, $user);
        } else {
            $form = $this->createForm(PatientType::class, $user);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            // Rediriger vers la liste appropriée en fonction du rôle
            $roleName = $user->getRole()->getNom();
            if ($roleName === 'Médecin') {
                return $this->redirectToRoute('app_user_medecins', [], Response::HTTP_SEE_OTHER);
            } elseif ($roleName === 'Soignant') {
                return $this->redirectToRoute('app_user_soignants', [], Response::HTTP_SEE_OTHER);
            } else {
                return $this->redirectToRoute('app_user_patients', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'role' => $user->getRole()->getNom(), // Passer le rôle à la vue
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $statut = $user->getStatut(); // Récupérer le statut de l'utilisateur

        $entityManager->remove($user);
        $entityManager->flush();

        // Vérifier si le statut est "En attente"
        if ($statut === 'En attente') {
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        // Vérifier si le statut est "Approuvé" et rediriger selon le rôle
        if ($statut === 'Approuvé') {
            $roleName = $user->getRole()->getNom();

            return match ($roleName) {
                'Médecin' => $this->redirectToRoute('app_user_medecins', [], Response::HTTP_SEE_OTHER),
                'Soignant' => $this->redirectToRoute('app_user_soignants', [], Response::HTTP_SEE_OTHER),
                default => $this->redirectToRoute('app_user_patients', [], Response::HTTP_SEE_OTHER),
            };
        }

        // Si le statut n'est ni "En attente" ni "Approuvé", redirection par défaut
        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }








}
