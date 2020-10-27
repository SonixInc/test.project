<?php


namespace App\Controller;

use App\Chat\Pusher;
use App\Entity\Message;
use App\Entity\User;
use App\Form\MessageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ZMQ;
use ZMQContext;

/**
 * Class ChatController
 *
 * @package App\Controller
 */
class ChatController extends AbstractController
{
    /**
     * @Route("chat", name="chat", methods={"GET|POST"})
     *
     * @param Request                $request
     *
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $messages = $em->getRepository(Message::class)->findAll();


        if ($request->isMethod('POST')) {
            $content = $request->request->get('content');

            $message = new Message();
            $message->setContent($content);
            $message->setUser($user);
            $message->setCreatedAt(new \DateTimeImmutable());

            $em->persist($message);
            $em->flush();

            return $this->redirectToRoute('chat');
        }



        return $this->render('chat/index.html.twig', [
            'messages' => $messages
        ]);
    }


}