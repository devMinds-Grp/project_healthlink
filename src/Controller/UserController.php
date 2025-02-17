<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\User1Type;
use App\Form\PatientType;
use App\Form\MedecinType;
use App\Form\SoignantType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Role;

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
            ]);
        }

        // Récupérer les utilisateurs ayant ce rôle
        $medecins = $entityManager
            ->getRepository(User::class)
            ->findBy(['role' => $roleMedecin]);

        return $this->render('user/medecins.html.twig', [
            'medecins' => $medecins,
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
    public function new(Request $request, EntityManagerInterface $entityManager): Response
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

            $user->setStatut('approuvé');
            $entityManager->persist($user);
            $entityManager->flush();

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

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
            'role' => $user->getRole()->getNom(), // Envoie le rôle à la vue
        ]);
    }


    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        // Vérifier si le statut est "en attente" et le modifier en "approuvé"
        if ($user->getStatut() === 'en attente') {
            $user->setStatut('approuvé');
            $entityManager->flush();
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
