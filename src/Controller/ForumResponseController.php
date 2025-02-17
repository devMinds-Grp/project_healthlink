<?php

namespace App\Controller;

use App\Entity\Forum;
use App\Entity\ForumResponse;
use App\Form\ForumResponseType;
use App\Repository\ForumResponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/forum/response')]
class ForumResponseController extends AbstractController
{
    #[Route('/{id}/new', name: 'forum_response_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Forum $forum, EntityManagerInterface $entityManager): Response
    {
        $forumResponse = new ForumResponse();
        $form = $this->createForm(ForumResponseType::class, $forumResponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $forumResponse->setForum($forum);
            $forumResponse->setDate(new \DateTime());
            $entityManager->persist($forumResponse);
            $entityManager->flush();

            return $this->redirectToRoute('forum_show', ['id' => $forum->getId()]);
        }

        return $this->render('forum_response/new.html.twig', [
            'forum' => $forum,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'forum_response_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ForumResponse $forumResponse, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ForumResponseType::class, $forumResponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('forum_show', ['id' => $forumResponse->getForum()->getId()]);
        }

        return $this->render('forum_response/edit.html.twig', [
            'forumResponse' => $forumResponse,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'forum_response_delete', methods: ['POST'])]
    public function delete(Request $request, ForumResponse $forumResponse, EntityManagerInterface $entityManager): Response
    {
        $forumId = $forumResponse->getForum()->getId();
        if ($this->isCsrfTokenValid('delete'.$forumResponse->getId(), $request->request->get('_token'))) {
            $entityManager->remove($forumResponse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('forum_show', ['id' => $forumId]);
    }
}