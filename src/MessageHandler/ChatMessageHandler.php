<?php

namespace App\MessageHandler;

use App\Entity\ChatMessage as EntityChatMessage;
use App\Message\ChatMessage;
use App\Repository\ChatMessageRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ChatMessageHandler implements MessageHandlerInterface
{
    private $chatMessageRepository;

    public function __construct(ChatMessageRepository $chatMessageRepository)
    {
        $this->chatMessageRepository = $chatMessageRepository;
    }

    public function __invoke(ChatMessage $message)
    {
        $entityMessage = new EntityChatMessage();
        $entityMessage->setSender($message->getSender());
        $entityMessage->setRecipient($message->getRecipient());
        $entityMessage->setMessage($message->getMessage());

        $this->chatMessageRepository->save($entityMessage);
    }
}