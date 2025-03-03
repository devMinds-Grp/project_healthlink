<?php
// src/Controller/DonneurController.php
namespace App\Controller;

use App\Entity\Donneur;
use App\Form\DonneurType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DonneurController extends AbstractController
{
    #[Route('/donneur', name: 'donneur_form', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $donneur = new Donneur();
        $form = $this->createForm(DonneurType::class, $donneur);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérification des critères de don
            $age = $donneur->getAge();
            $poids = $donneur->getPoids();
            $estEnBonneSante = $donneur->getEstEnBonneSante();

            // Conditions pour vérifier l'éligibilité
            $eligible = true;
            $message = '';

            if ($age < 18 || $age > 65) {
                $eligible = false;
                $message = 'Vous devez avoir entre 18 et 65 ans pour donner du sang.';
            } elseif ($poids < 50) {
                $eligible = false;
                $message = 'Vous devez peser au moins 50 kg pour donner du sang.';
            } elseif (!$estEnBonneSante) {
                $eligible = false;
                $message = 'Vous devez être en bonne santé pour donner du sang.';
            }

            // Si éligible, enregistre les données
            if ($eligible) {
                // Sauvegarde dans la base de données ou logique supplémentaire
                // Exemple : $entityManager->persist($donneur); $entityManager->flush();

                $this->addFlash('success', 'Merci de vouloir donner votre sang!');
            } else {
                $this->addFlash('error', $message);
            }

            return $this->redirectToRoute('donneur_form');
        }

        return $this->render('donneur.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
