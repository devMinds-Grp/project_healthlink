<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Entity\User;
use App\Service\SmsService;
use App\Enum\StatutRdv;
use App\Form\AppointmentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/appointment')]
final class AppointmentController extends AbstractController
{
    #[Route( name: 'app_appointment_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, Security $security): Response
  {
    // Récupérer l'utilisateur connecté
    $user = $security->getUser();

    // Vérifier si l'utilisateur est connecté et a le rôle "Patient"
    if ($user && in_array('ROLE_PATIENT', $user->getRoles())) {
        // Récupérer les rendez-vous uniquement pour ce patient
        $appointments = $entityManager
            ->getRepository(Appointment::class)
            ->findBy(['patient' => $user]);
    } else {
        // Si l'utilisateur n'est pas un patient, rediriger ou afficher une liste vide
        $appointments = [];
    }

    return $this->render('appointment/index.html.twig', [
        'appointments' => $appointments,
    ]);
}

    #[Route('/new/{doctorId}', name: 'app_appointment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager , $doctorId , Security $security): Response
    {
        $doctor = $entityManager->getRepository(User::class)->find($doctorId);
        $patient = $security->getUser();

        $appointment = new Appointment();
        $appointment->setDoctor($doctor);
        $appointment->setPatient($patient);
        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($appointment);
            $entityManager->flush();

            return $this->redirectToRoute('app_appointment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('appointment/new.html.twig', [
            'appointment' => $appointment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_appointment_show', methods: ['GET'])]
    public function show(Appointment $appointment): Response
    {
        return $this->render('appointment/show.html.twig', [
            'appointment' => $appointment,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_appointment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Appointment $appointment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_appointment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('appointment/edit.html.twig', [
            'appointment' => $appointment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_appointment_delete', methods: ['POST'])]
    public function delete(Request $request, Appointment $appointment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$appointment->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($appointment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_appointment_index', [], Response::HTTP_SEE_OTHER);
    }

                #[Route('/appointment/mes-rdv', name: 'app_appointment_mes_rdv', methods: ['GET'])]
            public function mesRendezVous(EntityManagerInterface $entityManager, Security $security): Response
            {
                // Récupérer l'utilisateur connecté
                $user = $security->getUser();

                // Vérifier si l'utilisateur est un médecin
                if (in_array('ROLE_MéDECIN', $user->getRoles())) {
                    // Récupérer les rendez-vous uniquement pour ce médecin
                    $appointments = $entityManager
                        ->getRepository(Appointment::class)
                        ->findBy(['doctor' => $user]);
                } else {
                    // Si l'utilisateur n'est pas un médecin, rediriger ou afficher une liste vide
                    $appointments = [];
                }

                return $this->render('appointment/doctor/list_RDV.html.twig', [
                    'appointments' => $appointments,
                ]);
            }

            #[Route('/appointment/{id}/accept', name: 'app_appointment_accept', methods: ['POST'])]
            public function acceptAppointment(Appointment $appointment, EntityManagerInterface $entityManager, SmsService $smsService): Response
            {
                // Mettre à jour le statut du rendez-vous
                $appointment->setStatut(StatutRdv::CONFIRME);
        
                // Enregistrer les modifications
                $entityManager->flush();
        
                
                $patient = $appointment->getPatient();
                if ($patient && $patient->getNumTel()) {
                    $message = sprintf(
                        "Bonjour %s, votre rendez-vous du %s a été confirmé. Merci pour votre confiance!",
                        $patient->getPrenom(),
                        $appointment->getDate()->format('Y-m-d')
                    );
        
                    try {
                        $smsService->sendSms($patient->getNumTel(), $message);
                        $this->addFlash('success', 'Le rendez-vous a été confirmé et un SMS a été envoyé au patient.');
                    } catch (\Exception $e) {
                        $this->addFlash('error', 'Erreur lors de l\'envoi du SMS : ' . $e->getMessage());
                    }
                } else {
                    $this->addFlash('warning', 'Le patient n\'a pas de numéro de téléphone enregistré.');
                }
                
        
                return $this->redirectToRoute('app_appointment_mes_rdv');
            }
        
            #[Route('/appointment/{id}/reject', name: 'app_appointment_reject', methods: ['POST'])]
            public function rejectAppointment(Appointment $appointment, EntityManagerInterface $entityManager, SmsService $smsService): Response
            {
                // Mettre à jour le statut du rendez-vous
                $appointment->setStatut(StatutRdv::ANNULE);
        
                // Enregistrer les modifications
                $entityManager->flush();
        
                
                
                $patient = $appointment->getPatient();
                if ($patient && $patient->getNumTel()) {
                    $message = sprintf(
                        "Bonjour %s, votre rendez-vous du %s a été annulé. Veuillez nous contacter pour plus d'informations.",
                        $patient->getPrenom(),
                        $appointment->getDate()->format('Y-m-d')
                    );
        
                    try {
                        $smsService->sendSms($patient->getNumTel(), $message);
                        $this->addFlash('success', 'Le rendez-vous a été annulé et un SMS a été envoyé au patient.');
                    } catch (\Exception $e) {
                        $this->addFlash('error', 'Erreur lors de l\'envoi du SMS : ' . $e->getMessage());
                    }
                } else {
                    $this->addFlash('warning', 'Le patient n\'a pas de numéro de téléphone enregistré.');
                }
                
        
                return $this->redirectToRoute('app_appointment_mes_rdv');
            }
        }
