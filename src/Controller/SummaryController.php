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

/**
 * Class SummaryController
 *
 * @package App\Controller
 */
class SummaryController extends AbstractController
{
    /**
     * List of summaries
     *
     * @Route("summary", name="summary", methods={"GET"})
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('summary/index.html.twig');
    }

    /**
     * Create summary entity
     *
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
     * Show summary entity
     *
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