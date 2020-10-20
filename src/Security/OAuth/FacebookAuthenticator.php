<?php


namespace App\Security\OAuth;

use App\Entity\Network;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use League\OAuth2\Client\Provider\FacebookUser;
use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class FacebookAuthenticator
 *
 * @package App\Security\OAuth
 */
class FacebookAuthenticator extends SocialAuthenticator
{
    private $clientRegistry;
    private $em;
    private $router;

    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $em, RouterInterface $router)
    {
        $this->clientRegistry = $clientRegistry;
        $this->em = $em;
        $this->router = $router;
    }

    /**
     * Check route matches
     *
     * @param Request $request Http request
     *
     * @return bool
     */
    public function supports(Request $request): bool
    {
        return $request->attributes->get('_route') === 'oauth.facebook_check';
    }

    /**
     * @param Request $request Http request
     *
     * @return AccessToken|mixed
     */
    public function getCredentials(Request $request)
    {
        return $this->fetchAccessToken($this->getFacebookClient());
    }

    /**
     * @param mixed                 $credentials  User credentials
     * @param UserProviderInterface $userProvider User provider
     *
     * @return UserInterface|null
     */
    public function getUser($credentials, UserProviderInterface $userProvider): ?UserInterface
    {
        /** @var FacebookUser $facebookUser */
        $facebookUser = $this->getFacebookClient()
            ->fetchUserFromToken($credentials);

        $existingUser = $this->em->getRepository(User::class)
            ->findByNetworkIdentity($facebookUser->getId());
        if ($existingUser) {
            return $existingUser;
        }

        $networkName = 'facebook';
        $id = $facebookUser->getId();
        $username = $networkName . ':' . $id;

        $user = new User();
        $user->setUsername($username);

        $network = new Network($user, $id, $networkName);

        $user->addNetwork($network);

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    /**
     * @return OAuth2ClientInterface
     */
    private function getFacebookClient(): OAuth2ClientInterface
    {
        return $this->clientRegistry
            ->getClient('facebook_main');
    }

    /**
     * @param Request        $request Http request
     * @param TokenInterface $token   Interface for the user authentication information
     * @param                $providerKey
     *
     * @return RedirectResponse|Response|null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): RedirectResponse
    {
        $targetUrl = $this->router->generate('home');

        return new RedirectResponse($targetUrl);
    }

    /**
     * @param Request                 $request   Http request
     * @param AuthenticationException $exception Auth exception
     *
     * @return Response|null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    /**
     * Called when authentication is needed, but it's not sent.
     * This redirects to the 'login'.
     *
     * @param Request                      $request       Http request
     * @param AuthenticationException|null $authException Auth exception
     *
     * @return RedirectResponse
     */
    public function start(Request $request, AuthenticationException $authException = null): RedirectResponse
    {
        return new RedirectResponse(
            '/connect/', // might be the site, where users choose their oauth provider
            Response::HTTP_TEMPORARY_REDIRECT
        );
    }
}