<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ChatController
 *
 * @package App\Controller
 */
class ChatController extends AbstractController
{
    /**
     * @Route("chat", name="chat")
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('chat/index.html.twig');
    }
}