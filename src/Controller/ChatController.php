<?php


namespace App\Controller;

use App\Entity\Message;
use App\Entity\User;
use App\Service\MessageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * Load chat page
     *
     * @Route("chat", name="chat", methods={"GET|POST"})
     *
     * @param Request                $request        Http request
     * @param EntityManagerInterface $em             Entity manager
     * @param MessageService         $messageService Message service
     *
     * @return Response
     */
    public function index(Request $request, EntityManagerInterface $em, MessageService $messageService): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $messages = $em->getRepository(Message::class)->findAll();

        if ($request->isMethod('POST')) {
            $content = $request->request->get('content');

            $messageService->createMessage($content, $user);

            return $this->redirectToRoute('chat');
        }

        return $this->render('chat/index.html.twig', [
            'messages' => $messages,
            'token'    => $user->getId()
        ]);
    }


}