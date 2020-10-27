<?php


namespace App\Event;


use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class ChatMessageReadEvent
 *
 * @package App\Event
 */
class ChatMessageReadEvent extends Event
{
    public const NAME = 'chat.message.read';
    /**
     * @var User
     */
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}