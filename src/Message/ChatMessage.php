<?php

namespace App\Message;

use App\Entity\User;

class ChatMessage
{
    private $sender;
    private $recipient;
    private $message;

    public function __construct(User $sender, User $recipient, string $message)
    {
        $this->sender = $sender;
        $this->recipient = $recipient;
        $this->message = $message;
    }

    public function getSender(): User
    {
        return $this->sender;
    }

    public function getRecipient(): User
    {
        return $this->recipient;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}