<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        // Vérifier si l'utilisateur est banni et si la date de bannissement est dépassée
        if ($user->getBannedUntil() && $user->getBannedUntil() <= new \DateTime()) {
            $user->setBannedUntil(null); // Débannir l'utilisateur
        }

        // Si l'utilisateur est toujours banni, lancer une exception
        if ($user->isBanned()) {
            throw new CustomUserMessageAccountStatusException('Votre compte est banni jusqu\'au ' . $user->getBannedUntil()->format('Y-m-d H:i:s'));
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        // Ne rien faire
    }
}