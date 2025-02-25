<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use App\Form\PatientType;
use App\Form\MedecinType;
use App\Form\SoignantType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\Role;
use Imagine\Gd\Imagine;
use Imagine\Gd\Font;
use Imagine\Image\Box;
use Imagine\Image\Point;

final class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function profile(): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Vérifier si l'utilisateur est connecté
        if (!$user instanceof UserInterface) {
            return $this->redirectToRoute('app_login');
        }

        // Récupérer le nom du rôle de l'utilisateur
        $roleName = $user->getRole()->getNom();

        // Rediriger en fonction du rôle
        return match ($roleName) {
            'Médecin' => $this->redirectToRoute('app_user_medecins', [], Response::HTTP_SEE_OTHER),
            'Soignant' => $this->redirectToRoute('app_user_soignants', [], Response::HTTP_SEE_OTHER),
            default => $this->redirectToRoute('app_user_patients', [], Response::HTTP_SEE_OTHER),
        };
    }

    #[Route('/{id}/editprofile', name: 'app_user_editp', methods: ['GET', 'POST'])]
    public function editp(Request $request, User $user, EntityManagerInterface $entityManager, int $id, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);
        $plainPassword = $user->getmotDePasse();
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
            // Récupérer le mot de passe saisi dans le formulaire
            $password = $form->get('motDePasse')->getData();

            // Si un nouveau mot de passe est saisi, le hasher et le mettre à jour
            if ($password) {
                $hashedPassword = $passwordHasher->hashPassword($user, $password);
                $user->setmotDePasse($hashedPassword);
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

            // Mettre à jour le champ imageprofile dans l'entité User

            $entityManager->flush();

            // Rediriger vers une page de confirmation ou autre
            return $this->redirectToRoute('app_home');
        }

        return $this->render('user/Profile/editprofile.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'plainPassword' => $plainPassword,
            'role' => $user->getRole()->getNom(), // Passer le rôle à la vue
        ]);
    }

    private function generateInitialAvatar(string $initials): string
    {
        $uploadsDir = $this->getParameter('kernel.project_dir') . '/public/uploads';
        $filename = uniqid() . '.png';
        $filepath = $uploadsDir . '/' . $filename;

        $imagine = new Imagine();
        $size = new Box(100, 100);
        $image = $imagine->create($size);

        $fontPath = $this->getParameter('kernel.project_dir') . '/public/fonts/OpenSans-Bold.ttf';
        $font = new Font($fontPath, 24, new \Imagine\Image\Palette\Color\RGB('#ffffff'));

        $image->draw()->text($initials, $font, new Point(25, 50));
        $image->save($filepath);

        return $filename;
    }
}
