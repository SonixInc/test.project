<?php


namespace App\Controller;

use App\Entity\Chat;
use App\Entity\Message;
use App\Entity\User;
use App\Form\ChatType;
use App\Service\MessageService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * ChatController constructor.
     *
     * @param EntityManagerInterface $em Entity manager
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Load chat page
     *
     * @Route("chat", name="chat", methods={"GET"})
     *
     * @return Response
     */
    public function index(): Response
    {
        $chats = $this->em->getRepository(Chat::class)->findBy(['author' => $this->getUser()]);

        return $this->render('chat/index.html.twig', [
            'chats' => $chats,
        ]);
    }

    /**
     * Load me invited chats
     *
     * @Route("chat/invited", name="chat.invited", methods={"GET"})
     *
     * @return Response
     */
    public function meInvited(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $chats = $this->em->getRepository(Chat::class)->getMeInvitedChats($user->getId());

        return $this->render('chat/invited.html.twig', [
            'chats' => $chats,
        ]);
    }

    /**
     * Create chat
     *
     * @Route("chat/create", name="chat.create", methods={"GET|POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function create(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $chat = new Chat();
        $form = $this->createForm(ChatType::class, $chat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $chat->setAuthor($user);

            $this->em->persist($chat);
            $this->em->flush();

            return $this->redirectToRoute('chat');
        }

        return $this->render('chat/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("chat/{id}", name="chat.chat", methods={"GET|POST"}, requirements={"id" = "\d+"})
     *
     * @param Chat           $chat
     * @param Request        $request
     * @param MessageService $messageService
     *
     * @return Response
     */
    public function chat(Chat $chat, Request $request, MessageService $messageService): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($user !== $chat->getAuthor() && !\in_array($user, $chat->getUsers(), true)) {
            $this->addFlash('error', 'You are not member of this chat.');
            return $this->redirectToRoute('home');
        }

        if ($request->isMethod('POST')) {
            $content = $request->request->get('content');

            $message = $messageService->createMessage($content, $user, $chat);

            return $this->json([
                'message' => [
                    'id'      => $message->getId(),
                    'content' => $message->getContent(),
                ],
                'user'    => [
                    'username' => $user->getUsername(),
                ],
            ]);
        }

        return $this->render('chat/chat.html.twig', [
            'chat'     => $chat,
            'messages' => $chat->getMessages(),
            'token'    => $user->getId(),
        ]);
    }

    /**
     * @Route(
     *     "chat/{id}/message/{message_id}",
     *     name="chat.message.delete",
     *     methods={"DELETE"},
     *     requirements={"id" = "\d+", "message_id" = "\d+"}
     * )
     * @ParamConverter("message", options={"id" = "message_id"})
     *
     * @param Request $request
     * @param Chat    $chat
     * @param Message $message
     *
     * @return Response
     */
    public function deleteChatMessage(Request $request, Chat $chat, Message $message): Response
    {
        if ($this->isCsrfTokenValid('delete' . $message->getId(), $request->request->get('_token'))) {
            $this->em->remove($message);
            $this->em->flush();
        }

        return $this->redirectToRoute('chat.chat', ['id' => $chat->getId()]);
    }
}
