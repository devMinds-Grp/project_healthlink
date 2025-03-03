<?php

namespace App\Controller;

use App\Entity\User;
use App\Message\ChatMessage;
use App\Service\ChatService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class ChatController extends AbstractController
{
    private $entityManager;
    private $logger;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    #[Route('/chat/with/{recipientId}', name: 'app_chat_with_soignant', methods: ['GET'])]
    public function startChat(int $recipientId, ChatService $chatService, MessageBusInterface $bus, Request $request, Security $security): Response
    {
        $currentUser = $security->getUser();
        if (!$currentUser instanceof User) {
            throw $this->createAccessDeniedException('No authenticated user found.');
        }

        $recipient = $this->entityManager->getRepository(User::class)->find($recipientId);
        if (!$recipient) {
            throw $this->createNotFoundException('Recipient not found or invalid.');
        }

        // Determine roles and set up chat participants
        if (in_array('ROLE_PATIENT', $currentUser->getRoles())) {
            // Patient chatting with soignant
            if (!in_array('ROLE_SOIGNANT', $recipient->getRoles())) {
                throw $this->createAccessDeniedException('Only soignants can be chatted with by patients.');
            }
            $sender = $currentUser; // Patient
            $soignant = $recipient; // Soignant
        } elseif (in_array('ROLE_SOIGNANT', $currentUser->getRoles())) {
            // Soignant chatting with patient
            if (!in_array('ROLE_PATIENT', $recipient->getRoles())) {
                throw $this->createAccessDeniedException('Only patients can be chatted with by soignants.');
            }
            $sender = $currentUser; // Soignant
            $soignant = $sender; // Soignant (for template consistency)
            $patient = $recipient; // Patient
        } else {
            throw $this->createAccessDeniedException('Only patients or soignants can access this chat.');
        }

        $messages = $chatService->getMessagesWithUser($recipient); // Get all messages between current user and recipient

        if ($request->isXmlHttpRequest()) {
            $this->logger->info('Chat messages for AJAX:', [
                'messages' => $messages,
                'currentUserId' => $currentUser->getId(),
                'recipientId' => $recipient->getId(),
            ]);
            return $this->json([
                'messages' => array_map(function ($message) {
                    return [
                        'id' => $message->getId(),
                        'senderId' => $message->getSender()->getId(),
                        'senderName' => $message->getSender()->getNom() . ' ' . $message->getSender()->getPrenom(),
                        'message' => $message->getMessage(),
                        'createdAt' => $message->getCreatedAt()->format('d/m/Y H:i'),
                    ];
                }, $messages),
                'currentUserId' => $currentUser->getId(), // Add for debugging in JavaScript
            ]);
        }

        return $this->render('chat/index.html.twig', [
            'soignant' => $soignant,
            'messages' => $messages,
            'currentUser' => $currentUser,
            'recipient' => $recipient,
        ]);
    }

    #[Route('/chat/send/{recipientId}', name: 'app_chat_send', methods: ['POST'])]
    public function sendChat(int $recipientId, Request $request, ChatService $chatService, MessageBusInterface $bus, Security $security): Response
    {
        $currentUser = $security->getUser();
        if (!$currentUser instanceof User) {
            throw $this->createAccessDeniedException('No authenticated user found.');
        }

        $recipient = $this->entityManager->getRepository(User::class)->find($recipientId);
        if (!$recipient) {
            throw $this->createNotFoundException('Recipient not found or invalid.');
        }

        if (in_array('ROLE_PATIENT', $currentUser->getRoles())) {
            if (!in_array('ROLE_SOIGNANT', $recipient->getRoles())) {
                throw $this->createAccessDeniedException('Only soignants can receive messages from patients.');
            }
        } elseif (in_array('ROLE_SOIGNANT', $currentUser->getRoles())) {
            if (!in_array('ROLE_PATIENT', $recipient->getRoles())) {
                throw $this->createAccessDeniedException('Only patients can receive messages from soignants.');
            }
        } else {
            throw $this->createAccessDeniedException('Only patients or soignants can send messages.');
        }

        $message = $request->request->get('message');
        if (!$message) {
            throw new \RuntimeException('Message cannot be empty.');
        }

        $bus->dispatch(new ChatMessage($currentUser, $recipient, $message));
        $chatService->sendMessage($recipient, $message); // Ensure persistence

        return $this->redirectToRoute('app_chat_with_soignant', ['recipientId' => $recipientId]);
    }
}