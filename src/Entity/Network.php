<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Network
 *
 * @package App\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="user_networks", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"network", "identity"})
 * })
 */
class Network
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
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="networks")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $network;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $identity;

    /**
     * Network constructor.
     *
     * @param User   $user
     * @param string $network
     * @param string $identity
     */
    public function __construct(User $user, string $network, string $identity)
    {
        $this->user = $user;
        $this->network = $network;
        $this->identity = $identity;
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
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
     * @return self
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string
     */
    public function getNetwork(): ?string
    {
        return $this->network;
    }

    /**
     * @param string $network
     *
     * @return self
     */
    public function setNetwork(string $network): self
    {
        $this->network = $network;

        return $this;
    }

    /**
     * @return string
     */
    public function getIdentity(): ?string
    {
        return $this->identity;
    }

    /**
     * @param string $identity
     *
     * @return self
     */
    public function setIdentity(string $identity): self
    {
        $this->identity = $identity;

        return $this;
    }
}