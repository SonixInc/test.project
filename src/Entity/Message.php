<?php


namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Message
 *
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\MessageRepository")
 * @ORM\Table(name="messages")
 */
class Message
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $content;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var Chat
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Chat", inversedBy="messages", cascade={"all"})
     * @ORM\JoinColumn(name="chat_id", referencedColumnName="id", nullable=false)
     */
    private $chat;

    /**
     * @var ArrayCollection|UserMessage[]
     *
     * @ORM\OneToMany(targetEntity="UserMessage", mappedBy="message", orphanRemoval=true, cascade={"all"})
     */
    private $userMessages;

    /**
     * @var \DateTimeImmutable
     *
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    /**
     * Message constructor.
     */
    public function __construct()
    {
        $this->userMessages = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return $this
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

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
     * @return Chat
     */
    public function getChat(): ?Chat
    {
        return $this->chat;
    }

    /**
     * @param Chat $chat
     *
     * @return $this
     */
    public function setChat(Chat $chat): self
    {
        $this->chat = $chat;

        return $this;
    }

    /**
     * @return array
     */
    public function getUserMessages(): array
    {
        return $this->userMessages->toArray();
    }

    /**
     * @param UserMessage $userMessage
     *
     * @return $this
     */
    public function addUserMessage(UserMessage $userMessage): self
    {
        if (!$this->userMessages->contains($userMessage)) {
            $this->userMessages->add($userMessage);
        }

        return $this;
    }

    /**
     * @param UserMessage $userMessage
     *
     * @return $this
     */
    public function removeUserMessage(UserMessage $userMessage): self
    {
        $this->userMessages->removeElement($userMessage);

        return $this;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    /**
     * @param \DateTimeImmutable $created_at
     *
     * @return $this
     */
    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
}