<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Enum\Status;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/reclamation')]
final class ReclamationController extends AbstractController
{
    #[Route(name: 'app_reclamation_index', methods: ['GET'])]
    public function index(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
           
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename . '.' . $imageFile->guessExtension();
    
                try {
                    $imageFile->move(
                        $this->getParameter('upload_directory_reclamations'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Une erreur est survenue lors du téléchargement de l\'image.');
                    return $this->redirectToRoute('app_student_group_index_administrateur');
                }
    
                $reclamation->setImage($newFilename);
            } else {
                $reclamation->setImage("default.png");

            }
           

            $reclamation->setStatut(Status::EN_ATTENTE);
            $entityManager->persist($reclamation);
            $entityManager->flush();    

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reclamation_show', methods: ['GET'])]
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reclamation_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }










    //accepter reclamation
    #[Route('/{id}/accept', name: 'app_reclamation_accept')]
    public function accept( Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
      

        $reclamation->setStatut(Status::TERMINE);
        $entityManager->persist($reclamation);
            $entityManager->flush();
            $this->addFlash('success', 'La réclamation a été Accepté avec succès.');
            return $this->redirectToRoute('app_admin_reclamation_show', ["id"=>$reclamation->getId()], Response::HTTP_SEE_OTHER);
       
    }


    //refuser reclamation
    #[Route('/{id}/refuse', name: 'app_reclamation_refuse')]
    public function refuse(  Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
       
        $reclamation->setStatut(Status::REFUSE);
        $entityManager->persist($reclamation);
            $entityManager->flush();
            $this->addFlash('danger', 'La réclamation a été refusée avec succès.');
            return $this->redirectToRoute('app_admin_reclamation_show', ["id"=>$reclamation->getId()], Response::HTTP_SEE_OTHER);
    }























}
