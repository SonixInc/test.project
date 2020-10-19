<?php


namespace App\Controller\Auth\OAuth;


use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FacebookController
 *
 * @package App\Controller\Auth\OAuth
 */
class FacebookController extends AbstractController
{
    /**
     * @Route("/oauth/facebook", name="oauth.facebook")
     * @param ClientRegistry $registry
     * @return Response
     */
    public function connect(ClientRegistry $registry): Response
    {
        return $registry
            ->getClient('facebook_main')
            ->redirect(['public_profile'], []);
    }

    /**
     * @Route("/oauth/facebook/check", name="oauth.facebook_check")
     * @return Response
     */
    public function check(): Response
    {
        return $this->redirectToRoute('home');
    }
}