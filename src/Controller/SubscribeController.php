<?php


namespace App\Controller;


use App\Entity\User;
use App\Service\StripeService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Stripe\Exception\ApiErrorException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SubscribeController
 *
 * @package App\Controller
 */
class SubscribeController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var StripeService
     */
    private $stripeService;

    /**
     * SubscribeController constructor.
     *
     * @param EntityManagerInterface $em
     * @param StripeService          $stripeService
     */
    public function __construct(EntityManagerInterface $em, StripeService $stripeService)
    {
        $this->em            = $em;
        $this->stripeService = $stripeService;
    }

    /**
     *
     * @Route("subscribe", name="subscribe")
     * @IsGranted("ROLE_USER")
     *
     * @return Response
     */
    public function index(): Response
    {
        $basic   = $this->getParameter('basic_subscribe_key');
        $premium = $this->getParameter('premium_subscribe_key');

        return $this->render('subscribe/index.html.twig', [
            'basic'   => $basic,
            'premium' => $premium
        ]);
    }

    /**
     * Subscribe
     *
     * @Route("subscribe/create", name="subscribe.create", methods={"GET|POST"})
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function subscribe(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($user->getSubscription()) {
            $this->addFlash('error', 'You are already subscribed.');
            return $this->redirectToRoute('home');
        }

        if ($request->isMethod('POST')) {
            $token = $request->request->get('stripeToken');
            $type  = $request->query->get('type');

            if (!$role = $this->getRoleForType($type)) {
                $this->addFlash('error', 'Unknown type.');
                return $this->redirectToRoute('home');
            }

            try {
                $customer     = $this->stripeService->createCustomer($token);
                $subscription = $this->stripeService->createSubscription($customer, $type);

                $subscription->setUser($user);
                $user->addRole($role);

                $this->em->persist($subscription);
                $this->em->flush();
            } catch (ApiErrorException $e) {
                $this->addFlash('error', 'Failed request.');
            }

        }

        return $this->render('subscribe/subscribe.html.twig');
    }

    /**
     * Cancel subscription
     *
     * @Route("subscribe/cancel", name="subscribe.cancel", methods={"POST"})
     * @IsGranted("ROLE_USER")
     *
     * @return Response
     */
    public function cancel(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$subscription = $user->getSubscription()) {
            $this->addFlash('notice', 'You are not subscribed.');
            return $this->redirectToRoute('home');
        }

        try {
            $this->stripeService->cancelSubscription($subscription->getId());
            $this->addFlash('error', 'You are not subscribed.');
            return $this->redirectToRoute('home');
        } catch (ApiErrorException $e) {
            $this->addFlash('error', 'Failed request.');
        }

        return $this->redirectToRoute('home');
    }

    /**
     * Validate subscription type and return role of this type
     *
     * @param string $type Subscription type
     *
     * @return false|string
     */
    private function getRoleForType(string $type)
    {
        switch ($type) {
            case $this->getParameter('basic_subscribe_key'):
                return User::ROLE_SUBSCRIBER;
                break;
            case $this->getParameter('premium_subscribe_key'):
                return User::ROLE_PREMIUM_SUBSCRIBER;
                break;
            default:
                return false;
        }
    }
}