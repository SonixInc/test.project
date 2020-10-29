<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class UserMessages
 *
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\UserMessageRepository")
 * @ORM\Table(name="user_messages", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"message_id", "user_id"})
 * })
 */
class UserMessage
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @var Message
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Message", inversedBy="userMessages")
     * @ORM\JoinColumn(name="message_id", referencedColumnName="id", nullable=false)
     */
    private $message;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $viewed;

    /**
     * UserMessage constructor.
     *
     * @param User    $user
     * @param Message $message
     */
    public function __construct(User $user, Message $message)
    {
        $this->user = $user;
        $this->message = $message;
        $this->viewed = false;

    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Message
     */
    public function getMessage(): ?Message
    {
        return $this->message;
    }

    /**
     * @param Message $message
     *
     * @return $this
     */
    public function setMessage(Message $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return $this
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return bool
     */
    public function isViewed(): ?bool
    {
        return $this->viewed;
    }

    /**
     * @param bool $viewed
     *
     * @return $this
     */
    public function setViewed(bool $viewed): self
    {
        $this->viewed = $viewed;

        return $this;
    }
}