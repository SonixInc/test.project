<?php


namespace App\EventSubscriber;

use App\Entity\User;
use App\Entity\UserMessage;
use App\Event\ChatMessageReadEvent;
use App\Repository\UserMessageRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ChatSubscriber
 *
 * @package App\EventSubscriber
 */
class ChatSubscriber implements EventSubscriberInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var UserMessageRepository
     */
    private $userMessageRepository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * ChatSubscriber constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->userRepository = $em->getRepository(User::class);
        $this->userMessageRepository = $em->getRepository(UserMessage::class);
        $this->em = $em;
    }

    /**
     * @return \string[][]
     */
    public static function getSubscribedEvents(): array
    {
       return [
            ChatMessageReadEvent::class => ['onMessageRead']
       ];
    }

    /**
     * @param ChatMessageReadEvent $event
     */
    public function onMessageRead(ChatMessageReadEvent $event): void
    {
        if (!$user = $this->userRepository->find($event->getUserId())) {
            throw new \DomainException('User is not found.');
        }

        /** @var UserMessage[] $messages */
        $messages = $this->userMessageRepository->findUserUnreadMessages($user);

        foreach ($messages as $message) {
            $message->setViewed(true);
        }

        $this->em->flush();
    }
}