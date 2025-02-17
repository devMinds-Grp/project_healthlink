<?php

namespace App\Controller;

use App\Entity\BloodDonation;
use App\Form\BloodDonationType;
use App\Repository\BloodDonationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/blood/donation')]
final class BloodDonationController extends AbstractController
{
    #[Route(name: 'app_blood_donation_index', methods: ['GET'])]
    public function index(BloodDonationRepository $bloodDonationRepository): Response
    {
        return $this->render('blood_donation/index.html.twig', [
            'blood_donations' => $bloodDonationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_blood_donation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $bloodDonation = new BloodDonation();
        $form = $this->createForm(BloodDonationType::class, $bloodDonation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($bloodDonation);
            $entityManager->flush();

            return $this->redirectToRoute('app_blood_donation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('blood_donation/new.html.twig', [
            'blood_donation' => $bloodDonation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_blood_donation_show', methods: ['GET'])]
    public function show(BloodDonation $bloodDonation): Response
    {
        return $this->render('blood_donation/show.html.twig', [
            'blood_donation' => $bloodDonation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_blood_donation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BloodDonation $bloodDonation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BloodDonationType::class, $bloodDonation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_blood_donation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('blood_donation/edit.html.twig', [
            'blood_donation' => $bloodDonation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_blood_donation_delete', methods: ['POST'])]
    public function delete(Request $request, BloodDonation $bloodDonation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bloodDonation->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($bloodDonation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_blood_donation_index', [], Response::HTTP_SEE_OTHER);
    }
}
