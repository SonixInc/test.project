<?php


namespace App\EventListener;


use App\Entity\Company;
use App\Entity\Summary;
use App\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Security;

/**
 * Class SummaryUserListener
 *
 * @package App\EventListener
 */
class SummaryUserListener
{
    /**
     * @var Security
     */
    private $security;

    /**
     * CompanyUserListener constructor.
     *
     * @param Security $security Get the authorization user
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Summary) {
            return;
        }

        /** @var User $user */
        if ($user = $this->security->getUser()) {
            $user->setRoles(User::ROLE_WORKER);
            $entity->setUser($user);
        }
    }
}