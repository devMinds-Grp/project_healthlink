<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Security;
use App\Form\PatientType;
use App\Form\MedecinType;
use App\Form\SoignantType;
use App\Entity\User;
use App\Entity\Role;
use Psr\Log\LoggerInterface;

final class AuthentificationController extends AbstractController
{
    #[Route('/inscription/confirmation', name: 'app_inscription_confirmation')]
    public function confirmation(): Response
    {
        return $this->render('authentification/confirmation.html.twig');
    }

    #[Route('/login1', name: 'app_login1', methods: ['GET', 'POST'])]
    public function login(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        Security $security,
        LoggerInterface $logger // Ajoutez le LoggerInterface
    ): Response {
        // Si l'utilisateur est déjà authentifié, redirigez-le vers la page d'accueil
        if ($security->getUser()) {
            $logger->info('Utilisateur déjà authentifié, redirection vers app_admin.');
            return $this->redirectToRoute('app_admin');
        }
    
        // Créer un formulaire de connexion (vous pouvez utiliser un formulaire Symfony ou une simple requête POST)
        $email = $request->request->get('email');
        $motDePasse = $request->request->get('motDePasse');
    
        if ($request->isMethod('POST')) {
            $logger->info('Tentative de connexion avec email: ' . $email);
    
            // Valider les champs email et mot de passe
            if (empty($email) || empty($motDePasse)) {
                $logger->warning('Champs email ou mot de passe vides.');
                $this->addFlash('error', 'Veuillez remplir tous les champs.');
                return $this->redirectToRoute('app_login');
            }
    
            // Récupérer l'utilisateur par son email
            $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
    
            if (!$user) {
                $logger->warning('Aucun utilisateur trouvé avec cet email: ' . $email);
                $this->addFlash('error', 'Email ou mot de passe incorrect.');
                return $this->redirectToRoute('app_login');
            }
    
            // Vérifier le mot de passe
            if (!$passwordHasher->isPasswordValid($user, $motDePasse)) {
                $logger->warning('Mot de passe incorrect pour l\'utilisateur: ' . $email);
                $this->addFlash('error', 'Email ou mot de passe incorrect.');
                return $this->redirectToRoute('app_login');
            }
    
            // Vérifier le statut de l'utilisateur
            if ($user->getStatut() !== 'approuvé') {
                $logger->warning('Compte non approuvé pour l\'utilisateur: ' . $email);
                $this->addFlash('error', 'Votre compte n\'est pas encore approuvé.');
                return $this->redirectToRoute('app_login');
            }
    
            // Authentifier l'utilisateur
            $security->login($user);
            $logger->info('Utilisateur authentifié avec succès: ' . $email);
    
            // Rediriger en fonction du rôle
            $role = $user->getRole()->getNom();
            $logger->info('Rôle de l\'utilisateur: ' . $role);
    
            if ($role === 'Médecin') {
                return $this->redirectToRoute('app_user_medecins');
            } elseif ($role === 'Soignant') {
                return $this->redirectToRoute('app_user_soignants');
            } else {
                return $this->redirectToRoute('app_user_patients');
            }
        }
    
        // Afficher le formulaire de connexion
        $logger->info('Affichage du formulaire de connexion.');
        return $this->render('authentification/login.html.twig');
    }
    #[Route('/register1', name: 'app_register1', methods: ['GET', 'POST'])]
    public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();

        $roleName = $request->query->get('role', 'Patient');
        $role = $entityManager->getRepository(Role::class)->findOneBy(['nom' => $roleName]);

        if (!$role) {
            throw $this->createNotFoundException("Le rôle '$roleName' n'existe pas.");
        }

        $user->setRole($role);

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
            // Encoder le mot de passe correctement
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getmotDePasse());
            $user->setmotDePasse($hashedPassword);

            if ($roleName === 'Médecin' || $roleName === 'Soignant') {
                $user->setStatut('en attente'); // En attente d'approbation de l'admin
            } else {
                $user->setStatut('approuvé'); // Approuvé immédiatement
            }

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_inscription_confirmation');
        }

        return $this->render('authentification/register.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'role' => $roleName,
        ]);
    }
    

}
