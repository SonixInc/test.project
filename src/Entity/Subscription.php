<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Subscriber
 *
 * @package App\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="user_subscribtions")
 */
class Subscription
{
    /**
     * @var string
     *
     * @ORM\Id()
     * @ORM\Column(type="string")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     */
    private $customer_id;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $canceled;

    /**
     * @var \DateTimeImmutable
     *
     * @ORM\Column(type="datetime_immutable")
     */
    private $current_period_start;

    /**
     * @var \DateTimeImmutable
     *
     * @ORM\Column(type="datetime_immutable")
     */
    private $current_period_end;

    /**
     * @var User
     *
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="subscription")
     */
    private $user;

    /**
     * @return string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerId(): ?string
    {
        return $this->customer_id;
    }

    /**
     * @param string $customer_id
     *
     * @return $this
     */
    public function setCustomerId(string $customer_id): self
    {
        $this->customer_id = $customer_id;

        return $this;
    }

    /**
     * @return bool
     */
    public function isCanceled(): ?bool
    {
        return $this->canceled;
    }

    /**
     * @param bool $canceled
     *
     * @return $this
     */
    public function setCanceled(bool $canceled): self
    {
        $this->canceled = $canceled;

        return $this;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCurrentPeriodStart(): ?\DateTimeImmutable
    {
        return $this->current_period_start;
    }

    /**
     * @param \DateTimeImmutable $current_period_start
     *
     * @return $this
     */
    public function setCurrentPeriodStart(\DateTimeImmutable $current_period_start): self
    {
        $this->current_period_start = $current_period_start;

        return $this;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCurrentPeriodEnd(): ?\DateTimeImmutable
    {
        return $this->current_period_end;
    }

    /**
     * @param \DateTimeImmutable $current_period_end
     *
     * @return $this
     */
    public function setCurrentPeriodEnd(\DateTimeImmutable $current_period_end): self
    {
        $this->current_period_end = $current_period_end;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
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
}