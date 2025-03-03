<?php

namespace App\Controller;

use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/notification')]
class NotificationController extends AbstractController
{
    #[Route('/read/{id}', name: 'app_notification_read', methods: ['GET'])]
    public function read(int $id, EntityManagerInterface $entityManager): Response
    {
        $notification = $entityManager->getRepository(Notification::class)->find($id);
        
        if (!$notification || $notification->getUser() !== $this->getUser()) {
            throw $this->createNotFoundException('Notification not found or access denied');
        }

        $notification->setIsRead(true);
        $entityManager->flush();

        return $this->redirectToRoute('app_notifications');
    }

    #[Route('', name: 'app_notifications', methods: ['GET'])]
    public function index(): Response
    {
        $notifications = $this->getUser()->getNotifications();

        return $this->render('notification/index.html.twig', [
            'notifications' => $notifications,
        ]);
    }
}