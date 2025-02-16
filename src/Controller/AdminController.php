<?php

namespace App\Controller;

use App\Entity\Forum;
use App\Entity\ForumResponse;
use App\Repository\ForumRepository;
use App\Repository\ForumResponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin_base.html.twig', [
            'controller_name' => 'AdminController',
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
        if ($this->isCsrfTokenValid('approve'.$forum->getId(), $request->request->get('_token'))) {
            $forum->setIsApproved(true);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_forum_index');
    }

    #[Route('/admin/forum/{id}/disapprove', name: 'admin_forum_disapprove', methods: ['POST'])]
    public function disapproveForum(Request $request, Forum $forum, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('disapprove'.$forum->getId(), $request->request->get('_token'))) {
            $forum->setIsApproved(false);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_forum_index');
    }

    #[Route('/admin/forum/{id}/delete', name: 'admin_forum_delete', methods: ['POST'])]
    public function deleteForum(Request $request, Forum $forum, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$forum->getId(), $request->request->get('_token'))) {
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
        if ($this->isCsrfTokenValid('delete'.$response->getId(), $request->request->get('_token'))) {
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
}