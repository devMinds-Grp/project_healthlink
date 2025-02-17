<?php

namespace App\Controller;

use App\Entity\Care;
use App\Form\CareType;
use App\Repository\CareRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/care')]
final class CareController extends AbstractController
{
    #[Route(name: 'app_care_index', methods: ['GET'])]
    public function index(CareRepository $careRepository): Response
    {
        return $this->render('care/index.html.twig', [
            'cares' => $careRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_care_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $care = new Care();
        $form = $this->createForm(CareType::class, $care);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($care);
            $entityManager->flush();

            return $this->redirectToRoute('app_care_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('care/new.html.twig', [
            'care' => $care,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_care_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(int $id, CareRepository $careRepository): Response
    {
        $care = $careRepository->find($id);

        if (!$care) {
            throw $this->createNotFoundException('Care not found');
        }

        return $this->render('care/show.html.twig', [
            'care' => $care,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_care_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, int $id, CareRepository $careRepository, EntityManagerInterface $entityManager): Response
    {
        $care = $careRepository->find($id);

        if (!$care) {
            throw $this->createNotFoundException('Care not found');
        }

        $form = $this->createForm(CareType::class, $care);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_care_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('care/edit.html.twig', [
            'care' => $care,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_care_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Request $request, int $id, CareRepository $careRepository, EntityManagerInterface $entityManager): Response
    {
        $care = $careRepository->find($id);

        if (!$care) {
            throw $this->createNotFoundException('Care not found');
        }

        if ($this->isCsrfTokenValid('delete'.$care->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($care);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_care_index', [], Response::HTTP_SEE_OTHER);
    }
}