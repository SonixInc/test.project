<?php


namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Chat
 *
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\ChatRepository")
 * @ORM\Table(name="user_chats")
 */
class Chat
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
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id", nullable=false)
     */
    private $author;

    /**
     * @var ArrayCollection|User[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\User")
     * @ORM\JoinTable(name="user_chat_users",
     *     joinColumns={@ORM\JoinColumn(name="chat_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     * )
     */
    private $users;

    /**
     * @var ArrayCollection|Message[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="chat", orphanRemoval=true, cascade={"all"})
     */
    private $messages;

    /**
     * Chat constructor.
     */
    public function __construct()
    {
        $this->users    = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return User
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * @param User $author
     *
     * @return $this
     */
    public function setAuthor(User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return array
     */
    public function getUsers(): array
    {
        return $this->users->toArray();
    }

    /**
     * @param User $user
     *
     * @return $this
     */
    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }

        return $this;
    }

    /**
     * @param User $user
     *
     * @return $this
     */
    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        return $this->messages->toArray();
    }

    /**
     * @param Message $message
     *
     * @return $this
     */
    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
        }

        return $this;
    }

    /**
     * @param Message $message
     *
     * @return $this
     */
    public function removeMessage(Message $message): self
    {
        $this->messages->removeElement($message);

        return $this;
    }
}