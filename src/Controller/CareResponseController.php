<?php

namespace App\Controller;

use App\Entity\CareResponse;
use App\Form\CareResponseType;
use App\Repository\CareResponseRepository;
use App\Repository\CareRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;
use App\Entity\Notification;

#[Route('/care/response')]
final class CareResponseController extends AbstractController
{
    #[Route('/new', name: 'app_care_response_new', methods: ['POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        CareRepository $careRepository,
        Security $security
    ): Response {
        $contenuRep = $request->request->get('contenu_rep');
        $careId = $request->request->get('care_id');
    
        if (!$careId || empty($contenuRep)) {
            return $this->redirectToRoute('app_care_index');
        }
    
        $care = $careRepository->find($careId);
        if (!$care) {
            throw $this->createNotFoundException('Care not found');
        }
    
        $careResponse = new CareResponse();
        $careResponse->setContenuRep($contenuRep);
        $careResponse->setCare($care);
        $careResponse->setDateRep(new \DateTime('now', new \DateTimeZone('Africa/Tunis')));
    
        // Set the soignant (user) to the currently logged-in user
        $user = $security->getUser();
        if ($user && in_array('ROLE_SOIGNANT', $user->getRoles())) {
            $careResponse->setUser($user); // Set in CareResponse
            $careResponse->setSoignant($user); // Optionally set soignant if you want to use this property
        }
    
        // Set the patient from the associated Care entity
        $careResponse->setPatient($care->getPatient());
    
        // Persist the CareResponse
        $entityManager->persist($careResponse);
    
        // Create a notification for the patient
        $notification = new Notification();
        $notification->setUser($care->getPatient());
        $notification->setMessage("Un soignant a commenté votre demande de soin : \"$contenuRep\"");
        $notification->setCreatedAt(new \DateTime('now', new \DateTimeZone('Africa/Tunis')));
        $notification->setIsRead(false);
        $notification->setCareResponse($careResponse); // Keep this for consistency, if needed
        $notification->setSoignant($user); // Set the soignant directly in Notification
    
        // Debug to verify
        error_log("Notification created with Soignant: " . ($user ? $user->getNom() . " " . $user->getPrenom() : 'null'));
    
        // Persist the notification
        $entityManager->persist($notification);
    
        // Flush all changes to the database
        $entityManager->flush();
    
        // Redirect back to the modal
        return $this->redirectToRoute('app_care_index');
    }

    #[Route('/{id}/edit', name: 'app_care_response_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        int $id,
        CareResponseRepository $careResponseRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $careResponse = $careResponseRepository->find($id);

        if (!$careResponse) {
            throw $this->createNotFoundException('CareResponse not found');
        }

        $form = $this->createForm(CareResponseType::class, $careResponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            // Redirect back to the care demand's comments modal
            $care_id = $request->query->get('care_id');
            return $this->redirectToRoute('app_care_index');


            // Fallback if care_id is not provided
            return $this->redirectToRoute('app_care_response_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('care_response/edit.html.twig', [
            'care_response' => $careResponse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_care_response_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(
        Request $request,
        int $id,
        CareResponseRepository $careResponseRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $careResponse = $careResponseRepository->find($id);
    
        if (!$careResponse) {
            throw $this->createNotFoundException('CareResponse not found');
        }
    
        if ($this->isCsrfTokenValid('delete'.$careResponse->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($careResponse);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('app_care_index');
    }
}