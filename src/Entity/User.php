<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User entity
 *
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="users")
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 */
class User implements UserInterface
{
    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_WORKER = 'ROLE_WORKER';
    public const ROLE_COMPANY = 'ROLE_COMPANY';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=true)
     */
    private $password;

    /**
     * @var ArrayCollection|Feedback[]
     *
     * @ORM\OneToMany(targetEntity="Feedback", mappedBy="user", cascade={"remove"})
     */
    private $feedbacks;

    /**
     * @var ArrayCollection|Network[]
     *
     * @ORM\OneToMany(targetEntity="Network", mappedBy="user", orphanRemoval=true, cascade={"persist"})
     */
    private $networks;

    /**
     * @var ArrayCollection|Summary[]
     *
     * @ORM\OneToMany(targetEntity=Summary::class, mappedBy="user", orphanRemoval=true, cascade={"all"})
     */
    private $summaries;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->feedbacks = new ArrayCollection();
        $this->networks = new ArrayCollection();
        $this->summaries = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = self::ROLE_USER;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Feedback[]|ArrayCollection
     */
    public function getFeedbacks(): array
    {
        return $this->feedbacks->toArray();
    }

    /**
     * @param Feedback $feedback
     *
     * @return $this
     */
    public function addFeedback(Feedback $feedback): self
    {
        if (!$this->feedbacks->contains($feedback)) {
            $this->feedbacks->add($feedback);
        }

        return $this;
    }

    /**
     * @param Feedback $feedback
     *
     * @return $this
     */
    public function removeFeedback(Feedback $feedback): self
    {
        $this->feedbacks->removeElement($feedback);

        return $this;
    }

    /**
     * @return array
     */
    public function getNetworks(): array
    {
        return $this->networks->toArray();
    }

    /**
     * @param Network $network
     *
     * @return $this
     */
    public function addNetwork(Network $network): self
    {
        if (!$this->networks->contains($network)) {
            $this->networks->add($network);
        }

        return $this;
    }

    /**
     * @param Network $network
     *
     * @return $this
     */
    public function removeNetwork(Network $network): self
    {
        $this->networks->removeElement($network);

        return $this;
    }

    /**
     * @return ArrayCollection|Summary[]
     */
    public function getSummaries(): array
    {
        return $this->summaries->toArray();
    }

    /**
     * @param Summary $summary
     *
     * @return $this
     */
    public function addSummary(Summary $summary): self
    {
        if (!$this->summaries->contains($summary)) {
            $this->summaries->add($summary);
            $this->roles[] = self::ROLE_WORKER;
        }

        return $this;
    }

    /**
     * @param Summary $summary
     *
     * @return $this
     */
    public function removeSummary(Summary $summary): self
    {
        $this->summaries->removeElement($summary);

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->username;
    }
}
