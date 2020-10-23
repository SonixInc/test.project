<?php


namespace App\Service;

use App\Entity\Subscription;
use Stripe\Customer;
use Stripe\StripeClient;

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
     * StripeService constructor.
     *
     * @param StripeClient $stripeClient
     */
    public function __construct(StripeClient $stripeClient)
    {
        $this->stripeClient = $stripeClient;
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