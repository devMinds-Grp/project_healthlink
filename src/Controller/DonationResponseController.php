<?php

namespace App\Controller;

use App\Entity\DonationResponse;
use App\Form\DonationResponseType;
use App\Repository\DonationResponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/donation/response')]
final class DonationResponseController extends AbstractController
{
    #[Route(name: 'app_donation_response_index', methods: ['GET'])]
    public function index(DonationResponseRepository $donationResponseRepository): Response
    {
        return $this->render('donation_response/index.html.twig', [
            'donation_responses' => $donationResponseRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_donation_response_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $donationResponse = new DonationResponse();
        $form = $this->createForm(DonationResponseType::class, $donationResponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($donationResponse);
            $entityManager->flush();

            return $this->redirectToRoute('app_donation_response_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('donation_response/new.html.twig', [
            'donation_response' => $donationResponse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_donation_response_show', methods: ['GET'])]
    public function show(DonationResponse $donationResponse): Response
    {
        return $this->render('donation_response/show.html.twig', [
            'donation_response' => $donationResponse,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_donation_response_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DonationResponse $donationResponse, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DonationResponseType::class, $donationResponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_donation_response_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('donation_response/edit.html.twig', [
            'donation_response' => $donationResponse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_donation_response_delete', methods: ['POST'])]
    public function delete(Request $request, DonationResponse $donationResponse, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$donationResponse->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($donationResponse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_donation_response_index', [], Response::HTTP_SEE_OTHER);
    }
}
