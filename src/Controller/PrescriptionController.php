<?php

namespace App\Controller;

use App\Entity\Prescription;
use App\Form\PrescriptionType;
use App\Repository\PrescriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Mime\Address;


#[Route('/prescription')]
final class PrescriptionController extends AbstractController
{
    #[Route(name: 'app_prescription_index', methods: ['GET'])]
    public function index(PrescriptionRepository $prescriptionRepository): Response
    {
        return $this->render('prescription/index.html.twig', [
            'prescriptions' => $prescriptionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_prescription_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $prescription = new Prescription();
        $form = $this->createForm(PrescriptionType::class, $prescription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($prescription);
            $entityManager->flush();
            return $this->redirectToRoute('app_prescription_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('prescription/new.html.twig', [
            'prescription' => $prescription,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_prescription_show', methods: ['GET'])]
    public function show(Prescription $prescription): Response
    {
        return $this->render('prescription/show.html.twig', [
            'prescription' => $prescription,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_prescription_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Prescription $prescription, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PrescriptionType::class, $prescription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_prescription_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('prescription/edit.html.twig', [
            'prescription' => $prescription,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_prescription_delete', methods: ['POST'])]
    public function delete(Request $request, Prescription $prescription, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$prescription->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($prescription);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_prescription_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/{id}/send-email', name: 'app_prescription_send_email', methods: ['GET'])]
    public function sendEmail(int $id, MailerInterface $mailer, EntityManagerInterface $entityManager): Response
    {
        // Récupérer la prescription depuis la base de données
        $prescription = $entityManager->getRepository(Prescription::class)->find($id);

        if (!$prescription) {
            throw $this->createNotFoundException('Prescription non trouvée');
        }

        // Configurer Dompdf
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);

        // Générer le HTML à partir du template Twig
        $html = $this->renderView('emails/pdf_template.html.twig', [
            'prescription' => $prescription,
        ]);

        // Charger le HTML dans Dompdf
        $dompdf->loadHtml($html);

        // Définir le format du papier (A4 par défaut)
        $dompdf->setPaper('A4', 'portrait');

        // Rendre le PDF
        $dompdf->render();

        // Récupérer le contenu du PDF généré
        $pdfOutput = $dompdf->output();

        // Envoyer l'email avec le PDF en pièce jointe
        $email = (new TemplatedEmail())
            ->from(new Address('votre_email@example.com' ))
            ->to(new Address($prescription->getCardUser()->getEmail(), $prescription->getCardUser()->getNom()))
            ->subject('Votre prescription médicale')
            ->htmlTemplate('emails/prescription.html.twig')
            ->context([
                'prescription' => $prescription,
            ])
            ->attach($pdfOutput, 'prescription.pdf', 'application/pdf');

        $mailer->send($email);

        // Ajouter un message flash pour informer l'utilisateur
        $this->addFlash('success', 'L\'email a été envoyé avec succès.');

        // Rediriger l'utilisateur vers la liste des prescriptions
        return $this->redirectToRoute('app_prescription_index');
    }
    
}
