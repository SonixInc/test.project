<?php


namespace App\EventSubscriber;


use App\Entity\Affiliate;
use App\Entity\User;
use App\Service\MailerService;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class EasyAdminSubscriber
 *
 * @package App\EventSubscriber
 */
class EasyAdminSubscriber implements EventSubscriberInterface
{
    /**
     * @var MailerService
     */
    private $mailer;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * EasyAdminSubscriber constructor.
     *
     * @param MailerService            $mailer
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(MailerService $mailer, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->mailer = $mailer;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @return \string[][]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            AfterEntityUpdatedEvent::class => ['affiliateActiveMail'],
            BeforeEntityPersistedEvent::class => ['setUserPassword']
        ];
    }

    /**
     * Send message when affiliate is active
     *
     * @param AfterEntityUpdatedEvent $afterEvent After update event listener
     *
     * @throws TransportExceptionInterface Throws when an error occurs in sending message
     */
    public function affiliateActiveMail(AfterEntityUpdatedEvent $afterEvent): void
    {
        $entity = $afterEvent->getEntityInstance();

        if (!$entity instanceof Affiliate) {
            return;
        }

        if (!$entity->isActive()) {
            return;
        }

        $this->mailer->sendActivationEmail($entity);
    }

    /**
     * Sets a password when it not set
     *
     * @param BeforeEntityPersistedEvent $event
     */
    public function setUserPassword(BeforeEntityPersistedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if (!$entity instanceof User) {
            return;
        }

        if ($entity->getPassword()) {
            return;
        }

        $entity->setPassword($this->passwordEncoder->encodePassword(
            $entity,
            'secret'
        ));
    }
}