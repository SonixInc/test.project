<?php


namespace App\Service;

use App\Entity\Subscription;
use Stripe\Customer;
use Stripe\StripeClient;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Class StripeService
 *
 * @package App\Service
 */
class StripeService
{
    /**
     * @var StripeClient
     */
    private $stripeClient;
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    /**
     * StripeService constructor.
     *
     * @param StripeClient          $stripeClient
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(StripeClient $stripeClient, ParameterBagInterface $parameterBag)
    {
        $this->stripeClient = $stripeClient;
        $this->parameterBag = $parameterBag;
    }

    /**
     * @param string $token Stripe form token
     *
     * @return Customer
     * @throws \Stripe\Exception\ApiErrorException if the request fails
     */
    public function createCustomer(string $token): Customer
    {
        return $this->stripeClient->customers->create([
            'source' => $token
        ]);
    }

    /**
     * @param Customer $customer Stripe customer
     * @param string   $priceId  Stripe price id
     *
     * @return Subscription
     * @throws \Stripe\Exception\ApiErrorException if the request fails
     */
    public function createSubscription(Customer $customer, string $priceId): Subscription
    {
        $stripeSubscription = $this->stripeClient->subscriptions->create([
            'customer' => $customer,
            'items'    => [
                ['price' => $priceId],
            ],
        ]);

        $date = new \DateTimeImmutable();

        $subscription = new Subscription();
        $subscription->setId($stripeSubscription->id);
        $subscription->setCustomerId($stripeSubscription->customer);
        $subscription->setCanceled($stripeSubscription->cancel_at_period_end);
        $subscription->setCurrentPeriodStart($date->setTimestamp($stripeSubscription->current_period_start));
        $subscription->setCurrentPeriodEnd($date->setTimestamp($stripeSubscription->current_period_end));

        if ($this->parameterBag->get('premium_subscribe_key') === $priceId) {
            $subscription->setType(Subscription::PREMIUM);
        } else {
            $subscription->setType(Subscription::BASIC);
        }

        return $subscription;
    }

    /**
     * @param string $subscriptionId
     *
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function cancelSubscription(string $subscriptionId): void
    {
        $this->stripeClient->subscriptions->cancel($subscriptionId);
    }
}