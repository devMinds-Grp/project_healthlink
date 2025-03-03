<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Form\PatientType;
use App\Form\MedecinType;
use App\Form\SoignantType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Security\Core\Security; // Ajouter cette importation

use App\Entity\Role;
use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;


class SecurityController extends AbstractController
{
    #[Route('/connect/google', name: 'connect_google')]
    public function connectGoogle(ClientRegistry $clientRegistry): RedirectResponse
    {
        // Redirige vers Google pour l'authentification
        return $clientRegistry->getClient('google')->redirect([
            'openid',
            'email',
            'profile' // Scopes nécessaires
        ], []);
    }

    #[Route('/connect/google/check', name: 'connect_google_check')]
public function connectGoogleCheck(
    Request $request,
    ClientRegistry $clientRegistry,
    EntityManagerInterface $entityManager,
    UserPasswordHasherInterface $passwordHasher,
    Security $security // Ajouter cette dépendance
): Response {
    $client = $clientRegistry->getClient('google');
    try {
        // Récupère les informations de l'utilisateur depuis Google
        $googleUser = $client->fetchUser();

        $email = $googleUser->getEmail();
        $nom = $googleUser->getLastName();
        $prenom = $googleUser->getFirstName();

        // Vérifie si l'utilisateur existe déjà
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user) {
            // Crée un nouvel utilisateur uniquement avec le rôle Patient
            $user = new User();
            $user->setEmail($email);
            $user->setNom($nom ?: 'Inconnu');
            $user->setPrenom($prenom ?: 'Inconnu');

            // Générer un mot de passe aléatoire
            $randomPassword = bin2hex(random_bytes(10));
            $hashedPassword = $passwordHasher->hashPassword($user, $randomPassword);
            $user->setMotDePasse($hashedPassword);

            // Assigner le rôle "Patient"
            $rolePatient = $entityManager->getRepository(Role::class)->findOneBy(['nom' => 'Patient']);
            if (!$rolePatient) {
                throw $this->createNotFoundException("Le rôle 'Patient' n'existe pas.");
            }
            $user->setRole($rolePatient);

            // Statut approuvé immédiatement pour les patients
            $user->setStatut('approuvé');

            // Générer une image de profil par défaut avec initiales
            $initials = strtoupper(substr($user->getNom(), 0, 1) . substr($user->getPrenom(), 0, 1));
            $defaultAvatar = $this->generateInitialAvatar($initials);
            $user->setImageProfile($defaultAvatar);

            $entityManager->persist($user);
            $entityManager->flush();
        } else {
            // Vérifier que l'utilisateur existant est un Patient
            if ($user->getRole()->getNom() !== 'Patient') {
                $this->addFlash('error', 'La connexion avec Google est réservée aux patients.');
                return $this->redirectToRoute('app_login');
            }
        }

        // Connecter l'utilisateur manuellement
        $security->login($user, 'form_login', 'main');

        // Rediriger vers la page d'accueil
        return $this->redirectToRoute('app_home');

    } catch (\Exception $e) {
        $this->addFlash('error', 'Erreur lors de la connexion avec Google : ' . $e->getMessage());
        return $this->redirectToRoute('app_login');
    }
}
    #[Route('/not-approved', name: 'app_not_approved')]
    public function notApproved(): Response
    {
        return $this->render('security/not_approved.html.twig');
    }
    #[Route(path: '/', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
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

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/password-recovery', name: 'app_password_recovery', methods: ['POST'])]
    public function sendResetCode(Request $request, MailerInterface $mailer, EntityManagerInterface $entityManager): JsonResponse
    {
        // Récupérer l'email envoyé depuis le formulaire AJAX
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? null;

        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if (!$user) {
            return new JsonResponse(['message' => 'Email non trouvé'], 404);
        }

        // Générer un code à 4 chiffres aléatoires
        $code = random_int(1000, 9999);
        $user->setResetCode((string) $code); // Assurez-vous que le code est stocké en tant que chaîne de caractères
        $entityManager->flush();
        // Création de l'email
        $emailMessage = (new TemplatedEmail())
            ->from('amenichakroun62@gmail.com')  // Remplace par ton adresse d'expéditeur
            ->to($email)
            ->subject('Code de récupération de mot de passe')
            // ->text("Votre code de récupération est : $code");
            ->htmlTemplate('emails/reset_code.html.twig')
            ->context([
                'user' => $user,
                'code' => $code, // Passer l'utilisateur au template
            ]);
        try {
            $mailer->send($emailMessage);
            return new JsonResponse(['message' => 'Email envoyé avec succès', 'code' => $code], 200);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Erreur lors de l’envoi de l’email'], 500);
        }
    }
    #[Route('/verify-reset-code', name: 'app_verify_reset_code', methods: ['POST'])]
    public function verifyResetCode(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $code = $data['code'] ?? '';

        $user = $entityManager->getRepository(User::class)->findOneBy(['resetCode' => $code]);
        if (!$user) {
            return new JsonResponse(['message' => 'Code invalide'], 400);
        }

        return new JsonResponse(['redirect' => '/reset-password']);
    }


    #[Route('/change-password', name: 'app_change_password', methods: ['POST'])]
    public function changePassword(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $newPassword = $data['newPassword'] ?? '';
        $confirmPassword = $data['confirmPassword'] ?? '';
        $code = $data['code'] ?? ''; // Récupérer le code de réinitialisation

        // Vérifier que les mots de passe correspondent
        if ($newPassword !== $confirmPassword) {
            return new JsonResponse(['message' => 'Les mots de passe ne correspondent pas'], 400);
        }

        // Récupérer l'utilisateur en fonction du code de réinitialisation
        $user = $entityManager->getRepository(User::class)->findOneBy(['resetCode' => $code]);
        if (!$user) {
            return new JsonResponse(['message' => 'Code de réinitialisation invalide ou expiré'], 404);
        }

        // Hacher le nouveau mot de passe
        $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
        $user->setMotDePasse($hashedPassword);

        // Réinitialiser le code de réinitialisation
        $user->setResetCode(null);

        // Enregistrer les modifications dans la base de données
        $entityManager->flush();

        // Retourner une réponse JSON avec un message de succès
        return new JsonResponse(['message' => 'Mot de passe mis à jour avec succès', 'redirect' => '/']);
    }

}
