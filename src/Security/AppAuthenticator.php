<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\HttpClient;
use App\Repository\UserRepository; // Correction de l'importation
use Symfony\Component\Security\Core\Exception\AuthenticationException;



class AppAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';
    public const NOT_APPROVED_ROUTE = 'app_not_approved'; // Ajoutez cette constante pour la route de la page "non approuvé"

    public function __construct(private UrlGeneratorInterface $urlGenerator, private LoggerInterface $logger, private UserRepository $userRepository)
    {

    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->getPayload()->getString('email');
        $password = $request->getPayload()->getString('motDePasse'); // Changement ici pour correspondre à "motDePasse"
        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);

        // Créer le Passport sans vérifier le statut ici
        return new Passport(
            new UserBadge($email, function ($identifier) {
                // Récupérer l'utilisateur
                $user = $this->userRepository->findOneBy(['email' => $identifier]);

                // Si l'utilisateur n'existe pas, laisser Symfony gérer l'erreur
                if (!$user) {
                    throw new CustomUserMessageAuthenticationException('Utilisateur non trouvé.');
                }

                // Vérifier le statut de l'utilisateur
                if (strtolower($user->getStatut()) === 'en attente') {
                    throw new CustomUserMessageAuthenticationException('Votre compte est en attente d\'approbation.');
                }

                return $user; // Retourner l'utilisateur si tout est OK
            }),
            new PasswordCredentials($password),
            [
                new CsrfTokenBadge('authenticate', $request->getPayload()->getString('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $this->logger->info('Utilisateur connecté avec succès, redirection vers app_home.');
        return new RedirectResponse($this->urlGenerator->generate('app_home'));
    }


    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        $session = $request->getSession();

        if (
            $exception instanceof CustomUserMessageAuthenticationException
            && $exception->getMessageKey() === 'Votre compte est en attente d\'approbation.'
        ) {
            $session->set('auth_error', 'Votre compte est en attente d\'approbation.');
            return new RedirectResponse($this->urlGenerator->generate(self::NOT_APPROVED_ROUTE));
        }

        // Stocker l'erreur pour l'afficher sur la page de login
        $session->set('auth_error', $exception->getMessage());

        return new RedirectResponse($this->getLoginUrl($request));
    }

}
