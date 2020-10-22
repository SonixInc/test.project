<?php


namespace App\EventListener;

use App\Entity\Company;
use App\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Security;

/**
 * Class CompanyUserListener
 *
 * @package App\EventListener
 */
class CompanyUserListener
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

        if (!$entity instanceof Company) {
            return;
        }

        /** @var User $user */
        if ($user = $this->security->getUser()) {
            $user->addRole(User::ROLE_COMPANY);
            $entity->setUser($user);
        }
    }
}   