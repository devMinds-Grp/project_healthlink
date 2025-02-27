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

class AppAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator, private LoggerInterface $logger)
    {

    }

    public function authenticate(Request $request): Passport
    {

        $email = $request->getPayload()->getString('email');
        // $recaptchaToken = $request->request->get('recaptcha_token');

        // if (!$this->isRecaptchaValid($recaptchaToken)) {
        //     throw new CustomUserMessageAuthenticationException('Échec de la vérification reCAPTCHA.');
        // }

        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->getPayload()->getString('password')),
            [
                new CsrfTokenBadge('authenticate', $request->getPayload()->getString('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }

    // private function isRecaptchaValid(string $recaptchaToken): bool
    // {
    //     $recaptchaSecret = $_ENV['RECAPTCHA3_SECRET'];

    //     $client = HttpClient::create();
    //     $response = $client->request('POST', 'https://www.google.com/recaptcha/api/siteverify', [
    //         'body' => [
    //             'secret' => $recaptchaSecret,
    //             'response' => $recaptchaToken
    //         ]
    //     ]);

    //     $data = $response->toArray();

    //     // Vérifiez le score (par exemple, un score > 0.5 est considéré comme valide)
    //     return $data['success'] && $data['score'] > 0.5;
    // }
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $this->logger->info('Utilisateur connecté avec succès, redirection vers app_home.');
        return new RedirectResponse($this->urlGenerator->generate('app_home'));
    }


    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
