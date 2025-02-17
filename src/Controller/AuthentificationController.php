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

final class AuthentificationController extends AbstractController

{
    #[Route('/inscription/confirmation', name: 'app_inscription_confirmation')]
    public function confirmation(): Response
    {
        return $this->render('authentification/confirmation.html.twig');
    }

    #[Route('/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils, Security $security): Response
    {
        $user = $security->getUser();

        if ($user) {
            dump($user); // Vérifiez l'utilisateur
            $roles = $user->getRoles();
            dump($roles); // Vérifiez les rôles

            if (in_array('ROLE_ADMIN', $roles)) {
                dump('Redirection vers app_admin'); // Debug
                return $this->redirectToRoute('app_admin', [], Response::HTTP_SEE_OTHER);
            }

            if (in_array('ROLE_PATIENT', $roles) || in_array('ROLE_SOIGNANT', $roles) || in_array('ROLE_MEDECIN', $roles)) {
                dump('Redirection vers app_home'); // Debug
                return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
            }
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        dump($error, $lastUsername); // Debug

        return $this->render('authentification/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);

    }

    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
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


    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('Cette méthode peut être vide, Symfony gère automatiquement la déconnexion.');
    }
}
