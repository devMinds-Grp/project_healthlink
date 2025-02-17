<?php

namespace App\Controller;

use App\Repository\BloodDonationRepository;
use App\Entity\BloodDonation;
use App\Repository\DonationResponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('home/admin_base.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    
    #[Route('/admin/blood-donations', name: 'app_admin_blood_donations')]
    public function listBloodDonations(EntityManagerInterface $em): Response
    {
        $bloodDonations = $em->getRepository(BloodDonation::class)->findAll();

        return $this->render('blood_donation/admin1/list_blood_donations.html.twig', [
            'bloodDonations' => $bloodDonations,
        ]);
    }
    #[Route('/admin/blood_donation', name: 'app_admin_blood_donation')]
    public function index5(BloodDonationRepository $BloodDonationRepository): Response
    {
        return $this->render('blood_donation/admin1/list_blood_donations.html.twig', [
            'BloodDonation' => $BloodDonationRepository->findAll(),
        ]);
    }
    #[Route('/admin/donation_response', name: 'app_admin_Donation_Response')]
    public function index6(DonationResponseRepository $DonationResponseRepository): Response
    {
        return $this->render('donation_response/admin1/response.html.twig', [
            'DonationResponse' => $DonationResponseRepository->findAll(),
        ]);
    }
    #[Route('/blood-donation/approve/{id}', name: 'approve_blood_donation')]
public function approve(int $id, EntityManagerInterface $entityManager): Response
{
    $bloodDonation = $entityManager->getRepository(BloodDonation::class)->find($id);

    if (!$bloodDonation) {
        throw $this->createNotFoundException('Pas de demande de don trouvée pour cet ID');
    }

    // Exemple : Mettre à jour le statut
    $bloodDonation->setStatus('approved');
    $entityManager->flush();

    $this->addFlash('success', 'Demande approuvée avec succès.');

    return $this->redirectToRoute('app_blood_donation_list');
}

#[Route('/blood-donation/delete/{id}', name: 'delete_blood_donation')]
public function delete(int $id, EntityManagerInterface $entityManager): Response
{
    $bloodDonation = $entityManager->getRepository(BloodDonation::class)->find($id);

    if (!$bloodDonation) {
        throw $this->createNotFoundException('Pas de demande de don trouvée pour cet ID');
    }

    $entityManager->remove($bloodDonation);
    $entityManager->flush();

    $this->addFlash('success', 'Demande supprimée avec succès.');

    return $this->redirectToRoute('app_blood_donation_list');
}
}
