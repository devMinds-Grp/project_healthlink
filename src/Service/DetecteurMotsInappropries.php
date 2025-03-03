<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class DetecteurMotsInappropries
{
    private  $motsInappropries;

    // Le constructeur reçoit la liste des mots inappropriés
    public function __construct(ParameterBagInterface $params)
    {
        $this->motsInappropries = $params->get('mots_inappropries');
    }

    // Méthode pour détecter les mots inappropriés dans un texte
    public function detecter(string $contenu): bool
    {
        // Parcourir la liste des mots inappropriés
        foreach ($this->motsInappropries as $mot) {
            // Vérifier si le mot est présent dans le contenu
            if (stripos($contenu, $mot) !== false) {
                return true; // Un mot inapproprié a été trouvé
            }
        }
        return false; // Aucun mot inapproprié trouvé
    }
}