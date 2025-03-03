<?php

namespace App\Controller;

use App\Repository\BloodDonationRepository;
use App\Entity\BloodDonation;
use App\Repository\DonationResponseRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\Reclamation;
use App\Repository\CategoryRepository;
use App\Repository\ReclamationRepository;

use App\Entity\Appointment;
use App\Repository\AppointmentRepository;
use App\Entity\Prescription;
use App\Repository\PrescriptionRepository;

use App\Entity\Forum;
use App\Entity\Care;
use App\Entity\CareResponses;
use App\Entity\ForumResponse;
use App\Repository\ForumRepository;
use App\Repository\CareRepository;
use App\Repository\CareResponseRepository;
use App\Repository\ForumResponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;


final class AdminController extends AbstractController
{
    
    #[Route('/admin', name: 'app_admin')]
    public function index(UserRepository $userRepository): Response
    {
        $totalUsers = $userRepository->countTotalUsers();
        $pendingUsers = $userRepository->countPendingUsers();
        $doctors = $userRepository->countDoctors();
        $caregivers = $userRepository->countCaregivers();
        $doctorPatientPercentage = $userRepository->getDoctorAndPatientPercentage();
        $pendingApprovedPercentage = $userRepository->getPendingAndApprovedPercentage();

        return $this->render('home/statistic.html.twig', [
            'totalUsers' => $totalUsers,
            'pendingUsers' => $pendingUsers,
            'doctors' => $doctors,
            'caregivers' => $caregivers,
            'doctorPercentage' => $doctorPatientPercentage['doctorPercentage'],
            'patientPercentage' => $doctorPatientPercentage['patientPercentage'],
            'pendingPercentage' => $pendingApprovedPercentage['pendingPercentage'],
            'approvedPercentage' => $pendingApprovedPercentage['approvedPercentage'],
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

    // Remove or update the redundant method
    #[Route('/admin/blood_donation', name: 'app_admin_blood_donation')]
    public function indexx5(BloodDonationRepository $BloodDonationRepository): Response
    {
        return $this->render('blood_donation/admin1/list_blood_donations.html.twig', [
            'bloodDonations' => $BloodDonationRepository->findAll(), // Correct variable name
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

        return $this->redirectToRoute('app_admin_blood_donation');
    }



    #[Route('/admin/category/reclamation', name: 'app_admin_category_reclamation')]
    public function index5(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }
    #[Route('/admin/reclamation', name: 'app_admin_reclamation')]
    public function index3(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('reclamation/admin/index.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
        ]);
    }
    #[Route('/admin/reclamation/{id}', name: 'app_admin_reclamation_show')]
    public function show1(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/admin/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }
    #[Route('/admin/forums', name: 'admin_forum_index', methods: ['GET'])]
    public function listForums(ForumRepository $forumRepository): Response
    {
        // Récupérer tous les forums (approuvés et non approuvés)
        $forums = $forumRepository->findAll();

        return $this->render('admin/forum/index.html.twig', [
            'forums' => $forums,
        ]);
    }

    #[Route('/admin/forum/{id}/approve', name: 'admin_forum_approve', methods: ['POST'])]
    public function approveForum(Request $request, Forum $forum, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('approve' . $forum->getId(), $request->request->get('_token'))) {
            $forum->setIsApproved(true);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_forum_index');
    }

    #[Route('/admin/forum/{id}/disapprove', name: 'admin_forum_disapprove', methods: ['POST'])]
    public function disapproveForum(Request $request, Forum $forum, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('disapprove' . $forum->getId(), $request->request->get('_token'))) {
            $forum->setIsApproved(false);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_forum_index');
    }

    #[Route('/admin/forum/{id}/delete', name: 'admin_forum_delete', methods: ['POST'])]
    public function deleteForum(Request $request, Forum $forum, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $forum->getId(), $request->request->get('_token'))) {
            $entityManager->remove($forum);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_forum_index');
    }

    #[Route('/admin/responses', name: 'admin_response_index', methods: ['GET'])]
    public function listResponses(ForumResponseRepository $responseRepository): Response
    {
        return $this->render('admin/response/index.html.twig', [
            'responses' => $responseRepository->findAll(),
        ]);
    }

    #[Route('/admin/response/{id}/delete', name: 'admin_response_delete', methods: ['POST'])]
    public function deleteResponse(Request $request, ForumResponse $response, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $response->getId(), $request->request->get('_token'))) {
            $entityManager->remove($response);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_response_index');
    }
    #[Route('/admin/forum/{id}/comments', name: 'admin_forum_comments', methods: ['GET'])]
    public function showComments(Forum $forum): Response
    {
        // Récupérer les commentaires associés à ce forum
        $comments = $forum->getForomRep();

        return $this->render('admin/response/index.html.twig', [
            'forum' => $forum,
            'comments' => $comments,
        ]);
    }

    #[Route('/admin/comment/{id}/delete', name: 'admin_comment_delete', methods: ['POST'])]
    public function deleteComment(Request $request, ForumResponse $comment, EntityManagerInterface $entityManager): Response
    {
        // Vérifier le token CSRF pour la sécurité
        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
            // Supprimer le commentaire
            $entityManager->remove($comment);
            $entityManager->flush();

            // Ajouter un message flash pour informer l'utilisateur
            $this->addFlash('success', 'Le commentaire a été supprimé avec succès.');
        } else {
            // Si le token CSRF est invalide, afficher un message d'erreur
            $this->addFlash('error', 'Token CSRF invalide.');
        }

        // Rediriger vers la liste des commentaires du forum
        return $this->redirectToRoute('admin_forum_comments', ['id' => $comment->getForum()->getId()]);
    }


    #[Route('/admin/appointment', name: 'app_admin_appointment')]
    public function listappointment(EntityManagerInterface $em): Response
    {
        $appointment = $em->getRepository(appointment::class)->findAll();

        return $this->render('appointment/admin1/appointment.html.twig', [
            'appointment' => $appointment,
        ]);
    }

    #[Route('/appointment/approve/{id}', name: 'appointment_approve')]
    public function approve2(Appointment $appointment, EntityManagerInterface $entityManager): RedirectResponse
    {
        // Supposons que l'entité Care ait un champ "approved"
        $care->setApproved(true);
        $entityManager->flush();

        $this->addFlash('success', 'Le soin a été approuvé avec succès.');

        return $this->redirectToRoute('appointment_list'); // Redirige vers la liste des soins

    }

    #[Route('/appointment/delete/{id}', name: 'appointment_delete', methods: ['POST', 'GET'])]
    public function delete2(Appointment $appointment, EntityManagerInterface $entityManager): RedirectResponse
    {
        $entityManager->remove($appointment);
        $entityManager->flush();

        $this->addFlash('danger', 'Le soin a été supprimé.');

        return $this->redirectToRoute('app_admin_appointment'); // Redirige vers la liste des soins
    }


    #[Route('/admin/prescription', name: 'app_admin_prescription')]
    public function listprescription(EntityManagerInterface $em): Response
    {
        $prescription = $em->getRepository(prescription::class)->findAll();

        return $this->render('prescription/admin1/prescription.html.twig', [
            'prescription' => $prescription,
        ]);
    }

    #[Route('/prescription/approve/{id}', name: 'prescription_approve')]
    public function approve3(prescription $prescription, EntityManagerInterface $entityManager): RedirectResponse
    {
        // Supposons que l'entité Care ait un champ "approved"
        $prescription->setApproved(true);
        $entityManager->flush();

        $this->addFlash('success', 'Le soin a été approuvé avec succès.');

        return $this->redirectToRoute('prescription_list'); // Redirige vers la liste des soins

    }

    #[Route('/prescription/delete/{id}', name: 'prescription_delete', methods: ['POST', 'GET'])]
    public function delete3(prescription $prescription, EntityManagerInterface $entityManager): RedirectResponse
    {
        $entityManager->remove($prescription);
        $entityManager->flush();

        $this->addFlash('danger', 'Le soin a été supprimé.');

        return $this->redirectToRoute('app_admin_prescription'); // Redirige vers la liste des soins
    }

    #[Route('/admin/care', name: 'app_admin_care')]
    public function listcare(EntityManagerInterface $em): Response
    {
        $care = $em->getRepository(Care::class)->findAll();

        return $this->render('care/admin1/care.html.twig', [
            'care' => $care,
        ]);
    }

    #[Route('/care/approve/{id}', name: 'care_approve')]
    public function approve1(Care $care, EntityManagerInterface $entityManager): RedirectResponse
    {
        // Supposons que l'entité Care ait un champ "approved"
        $care->setApproved(true);
        $entityManager->flush();

        $this->addFlash('success', 'Le soin a été approuvé avec succès.');

        return $this->redirectToRoute('care_list'); // Redirige vers la liste des soins

    }

    #[Route('/care/delete/{id}', name: 'care_delete', methods: ['POST', 'GET'])]
    public function delete1(Care $care, EntityManagerInterface $entityManager): RedirectResponse
    {
        $entityManager->remove($care);
        $entityManager->flush();

        $this->addFlash('danger', 'Le soin a été supprimé.');

        return $this->redirectToRoute('app_admin_care'); // Redirige vers la liste des soins
    }

    #[Route('/admin/care-responses', name: 'admin_care_responses')]
    public function listCareResponse(CareResponseRepository $careResponseRepository): Response
    {
        $careResponses = $careResponseRepository->findAll();

        return $this->render('care_response/admin1/careresp.html.twig', [
            'careResponses' => $careResponses // est bien définie
        ]);
    }


}
