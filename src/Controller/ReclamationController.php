<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Enum\Status;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use App\Service\DeepLTranslationService;
use Doctrine\ORM\EntityManagerInterface;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Knp\Component\Pager\PaginatorInterface;
use ReCaptcha\ReCaptcha;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/reclamation')]
final class ReclamationController extends AbstractController
{
    #[Route(name: 'app_reclamation_index', methods: ['GET'])]
    public function index(ReclamationRepository $reclamationRepository,PaginatorInterface $paginator ,Request $request): Response
    {
        $query = $request->query->get('query');
        $status = $request->query->get('status');

        // Convert the status string to the corresponding enum value
        $statusEnum = null;
        if ($status) {
            $statusEnum = Status::from($status);
        }

        // Fetch reclamations based on the search query and status
        $reclamationsQuery = $reclamationRepository->findBySearchQueryAndStatusQuery($query, $statusEnum);
    
   $pagination = $paginator->paginate(
            $reclamationsQuery,
            $request->query->getInt('page', 1),
            2   // items per page
        );


        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $pagination
        ]);
    }



    #[Route('/reclamation/search', name: 'app_reclamation_search', methods: ['GET'])]
    public function search(ReclamationRepository $reclamationRepository, Request $request): JsonResponse
    {
        $query = $request->query->get('query');
        $status = $request->query->get('status');
    
        $statusEnum = $status ? Status::from($status) : null;
    
        $reclamations = $reclamationRepository->findBySearchQueryAndStatusQuery($query, $statusEnum)->getQuery()->getResult();
    
        $data = array_map(function ($reclamation) {
            return [
                'id' => $reclamation->getId(),
                'titre' => $reclamation->getTitre(),
                'description' => $reclamation->getDescription(),
                'image' => $reclamation->getImage(),
                'category' => $reclamation->getCategory()->getNom(),
                'statut' => [
                    'label' => $reclamation->getStatut()->label(),
                    'badgeClass' => $reclamation->getStatut()->badgeClass(),
                    'iconClass' => $reclamation->getStatut()->iconClass()
                ],
                'edit_url' => $this->generateUrl('app_reclamation_edit', ['id' => $reclamation->getId()])
            ];
        }, $reclamations);
    
        return $this->json($data);
    }



 
    #[Route('/new', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Traitement du formulaire
            $imageFile = $form->get('image')->getData();
    
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
    
                try {
                    $imageFile->move(
                        $this->getParameter('upload_directory_reclamations'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Une erreur est survenue lors du téléchargement de l\'image.');
                    return $this->redirectToRoute('app_reclamation_new');
                }
    
                $reclamation->setImage($newFilename);
            } else {
                $reclamation->setImage("default.png");
            }
    
            $reclamation->setStatut(Status::EN_ATTENTE);
            $entityManager->persist($reclamation);
            $entityManager->flush();
    
            $this->addFlash('success', 'Réclamation créée avec succès.');
            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }





    #[Route('/{id}', name: 'app_reclamation_show', methods: ['GET'])]
    public function show(Reclamation $reclamation, DeepLTranslationService $translationService, Request $request): Response
    {
        // Récupérer la langue cible depuis la requête (par défaut : anglais)
        $targetLanguage = $request->query->get('lang', 'EN');
    
        // Traduire les champs dynamiques
        $translatedDescription = $translationService->translate($reclamation->getDescription(), $targetLanguage);
        $translatedTitre = $translationService->translate($reclamation->getTitre(), $targetLanguage);
        
        // Générer le QR code
        $data = 'Titre : ' . $translatedTitre . ' | de category : ' . $reclamation->getCategory()->getNom() . ' | Description : ' . $translatedDescription;
        $result = Builder::create()
            ->writer(new \Endroid\QrCode\Writer\PngWriter())
            ->data($data)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(ErrorCorrectionLevel::Medium)
            ->size(300)
            ->margin(10)
            ->build();
    
        $dataUri = $result->getDataUri();
    
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
            'qr' => $dataUri,
            'translatedDescription' => $translatedDescription,
            'translatedTitre' => $translatedTitre,
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










 
    #[Route('/{id}/accept', name: 'app_reclamation_accept', methods: ['POST'])]
    public function accept(Reclamation $reclamation, EntityManagerInterface $entityManager): JsonResponse
    {


        $reclamation->setStatut(Status::TERMINE);
        $entityManager->persist($reclamation);
        $entityManager->flush();
    
        return new JsonResponse([
            'status' => 'success',
            'message' => 'La réclamation a été acceptée avec succès.',
            'newStatus' => $reclamation->getStatut()->label(),
            'badgeClass' => $reclamation->getStatut()->badgeClass(),
            'iconClass' => $reclamation->getStatut()->iconClass(),
        ]);
    }
    
    #[Route('/{id}/refuse', name: 'app_reclamation_refuse', methods: ['POST'])]
    public function refuse(Reclamation $reclamation, EntityManagerInterface $entityManager): JsonResponse
    {
        $reclamation->setStatut(Status::REFUSE);
        $entityManager->persist($reclamation);
        $entityManager->flush();
    
        return new JsonResponse([
            'status' => 'success',
            'message' => 'La réclamation a été refusée avec succès.',
            'newStatus' => $reclamation->getStatut()->label(),
            'badgeClass' => $reclamation->getStatut()->badgeClass(),
            'iconClass' => $reclamation->getStatut()->iconClass(),
        ]);
    }



















}
