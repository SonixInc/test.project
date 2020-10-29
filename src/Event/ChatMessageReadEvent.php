<?php


namespace App\Event;


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
     * @var int
     */
    private $userId;

    /**
     * ChatMessageReadEvent constructor.
     *
     * @param int $userId
     */
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }
}