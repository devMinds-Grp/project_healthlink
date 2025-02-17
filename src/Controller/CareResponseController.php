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


#[Route('/care/response')]
final class CareResponseController extends AbstractController
{
    #[Route(name: 'app_care_response_index', methods: ['GET'])]
    public function index(CareResponseRepository $careResponseRepository): Response
    {
        return $this->render('care_response/index.html.twig', [
            'care_responses' => $careResponseRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_care_response_new', methods: ['POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, CareRepository $careRepository, Security $security): Response
{
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

    // Set the logged-in user if available
    $user = $security->getUser();
    if ($user) {
        $careResponse->setUser($user);
    }

    $entityManager->persist($careResponse);
    $entityManager->flush();

    // Redirect back to the modal
    return $this->redirectToRoute('app_care_index');
}


    #[Route('/{id}', name: 'app_care_response_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(int $id, CareResponseRepository $careResponseRepository): Response
    {
        $careResponse = $careResponseRepository->find($id);

        if (!$careResponse) {
            throw $this->createNotFoundException('CareResponse not found');
        }

        return $this->render('care_response/show.html.twig', [
            'care_response' => $careResponse,
        ]);
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