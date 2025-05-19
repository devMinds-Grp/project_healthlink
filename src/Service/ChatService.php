<?php

namespace App\Service;

use App\Entity\ChatMessage;
use App\Entity\User;
use App\Entity\Notification;
use App\Repository\ChatMessageRepository;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;

class ChatService
{
    private $chatMessageRepository;
    private $security;
    private $entityManager;

    public function __construct(ChatMessageRepository $chatMessageRepository, Security $security, EntityManagerInterface $entityManager)
    {
        $this->chatMessageRepository = $chatMessageRepository;
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    public function sendMessage(User $recipient, string $message): ChatMessage
    {
        $sender = $this->security->getUser();
        if (!$sender instanceof User) {
            throw new \RuntimeException('No authenticated user found.');
        }

        $chatMessage = new ChatMessage();
        $chatMessage->setSender($sender);
        $chatMessage->setRecipient($recipient);
        // Encode the message using Base64
        $encodedMessage = base64_encode($message);
        $chatMessage->setMessage($encodedMessage);
        $chatMessage->setCreatedAt(new \DateTime('now'));

        $this->chatMessageRepository->save($chatMessage);

        // Create a notification for the soignant if the sender is a patient
        if (in_array('ROLE_PATIENT', $sender->getRoles()) && in_array('ROLE_SOIGNANT', $recipient->getRoles())) {
            $notification = new Notification();
            $notification->setUser($recipient); // Soignant receiving the notification
            // Use the plain message for the notification
            $notification->setMessage("Nouveau message de " . $sender->getNom() . " " . $sender->getPrenom() . ": \"" . $message . "\". Cliquez pour discuter.");
            $notification->setCreatedAt(new \DateTime('now'));
            $notification->setIsRead(false);
            $notification->setPatient($sender);
            $notification->setChatMessage($chatMessage);

            $this->entityManager->persist($notification);
            $this->entityManager->flush();
        }

        return $chatMessage;
    }

    public function getMessagesWithUser(User $otherUser): array
    {
        $currentUser = $this->security->getUser();
        if (!$currentUser instanceof User) {
            throw new \RuntimeException('No authenticated user found.');
        }

        $messages = $this->chatMessageRepository->findMessagesBetweenUsers($currentUser, $otherUser);
        // Decode messages before returning
        foreach ($messages as $message) {
            $decodedMessage = base64_decode($message->getMessage(), true);
            $message->setMessage($decodedMessage !== false ? $decodedMessage : $message->getMessage());
        }

        return $messages;
    }
}