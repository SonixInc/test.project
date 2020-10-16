<?php


namespace App\Controller;


use App\Entity\Summary;
use App\Form\SummaryType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SummaryController extends AbstractController
{
    /**
     * @Route("summary", name="summary", methods={"GET"})
     *
     * @param Request            $request   Http request
     * @param PaginatorInterface $paginator Knp paginator
     *
     * @return Response
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        /** @var Summary[] $summaries */
        $summaries = $paginator->paginate(
            $this->getDoctrine()->getRepository(Summary::class)->findAll(),
            $request->query->getInt('page', 1),
            1
        );

        return $this->render('summary/index.html.twig', [
            'summaries' => $summaries
        ]);
    }

    /**
     * @Route("summary/create", name="summary.create", methods={"GET|POST"})
     *
     * @param Request                $request Http request
     * @param EntityManagerInterface $em      Entity manager
     *
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $summary = new Summary();
        $form = $this->createForm(SummaryType::class, $summary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($summary);
            $em->flush();

            return $this->redirectToRoute('summary.show', ['id' => $summary->getId()]);
        }

        return $this->render('summary/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("summary/{id}", name="summary.show", methods={"GET"}, requirements={"id" = "\d+"})
     * @param Summary $summary Summary entity
     *
     * @return Response
     */
    public function show(Summary $summary): Response
    {
        return $this->render('summary/show.html.twig', [
            'summary' => $summary
        ]);
    }
}