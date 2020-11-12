<?php


namespace App\Service;

use App\Entity\Chat;
use App\Entity\Message;
use App\Entity\User;
use App\Entity\UserMessage;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class MessageService
 *
 * @package App\Service
 */
class MessageService
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * MessageService constructor.
     *
     * @param UserRepository         $userRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(UserRepository $userRepository, EntityManagerInterface $em)
    {
        $this->userRepository = $userRepository;
        $this->em = $em;
    }

    /**
     * @param string $content
     * @param User   $author
     * @param Chat   $chat
     *
     * @return Message
     */
    public function createMessage(string $content, User $author, Chat $chat): Message
    {
        $users = $this->userRepository->findAll();

        $message = new Message();
        $message->setChat($chat);
        $message->setContent($content);
        $message->setUser($author);
        $message->setCreatedAt(new \DateTimeImmutable());

        foreach ($users as $user) {
            $userMessage = new UserMessage($user, $message);
            $message->addUserMessage($userMessage);
        }

        $this->em->persist($message);
        $this->em->flush();

        return $message;
    }
}