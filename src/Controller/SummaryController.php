<?php


namespace App\Controller;


use App\Entity\Application;
use App\Entity\Job;
use App\Entity\Summary;
use App\Entity\User;
use App\Form\SummaryType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
            $this->getParameter('max_per_page')
        );

        return $this->render('summary/index.html.twig', [
            'summaries' => $summaries
        ]);
    }

    /**
     * List of auth user summaries
     *
     * @Route("summary/own", name="summary.own", methods={"GET"})
     *
     * @param Request            $request   Http request
     * @param PaginatorInterface $paginator Knp paginator
     *
     * @return Response
     */
    public function own(Request $request, PaginatorInterface $paginator): Response
    {
        /** @var Summary[] $summaries */
        $summaries = $paginator->paginate(
            $this->getDoctrine()->getRepository(Summary::class)->findBy(['user' => $this->getUser()]),
            $request->query->getInt('page', 1),
            $this->getParameter('max_per_page')
        );

        return $this->render('summary/index.html.twig', [
            'summaries' => $summaries
        ]);
    }

    /**
     * Create summary entity
     *
     * @Route("summary/create", name="summary.create", methods={"GET|POST"})
     * @IsGranted("ROLE_USER")
     *
     * @param Request                $request Http request
     * @param EntityManagerInterface $em      Entity manager
     *
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $summary = new Summary();
        $form = $this->createForm(SummaryType::class, $summary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $summary->setUser($user);

            $em->persist($summary);
            $em->flush();

            return $this->redirectToRoute('summary.show', ['id' => $summary->getId()]);
        }

        return $this->render('summary/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Edit summary
     *
     * @Route("summary/{id}/edit", name="summary.edit", methods={"GET|POST"}, requirements={"id" = "\d+"})
     *
     * @param Request                $request Http request
     * @param Summary                $summary Summary entity
     * @param EntityManagerInterface $em      Entity manager
     *
     * @return Response
     */
    public function edit(Request $request, Summary $summary, EntityManagerInterface $em): Response
    {
        if ($summary->getUser() !== $this->getUser()) {
            $this->addFlash('error', 'Access denied.');
            return $this->redirectToRoute('summary.show', ['id' => $summary->getId()]);
        }

        $form = $this->createForm(SummaryType::class, $summary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('summary.show', ['id' => $summary->getId()]);
        }

        return $this->render('summary/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Delete summary
     *
     * @Route("/summary/{id}/delete", name="summary.delete", methods={"DELETE"}, requirements={"id" = "\d+"})
     *
     * @param Request                $request Http request
     * @param Summary                $summary Summary entity
     * @param EntityManagerInterface $em      Entity manager
     *
     * @return Response
     */
    public function delete(Request $request, Summary $summary, EntityManagerInterface $em): Response
    {
        if ($summary->getUser() !== $this->getUser()) {
            $this->addFlash('error', 'Access denied.');
            return $this->redirectToRoute('summary.show', ['id' => $summary->getId()]);
        }

        if ($this->isCsrfTokenValid('delete' . $summary->getId(), $request->request->get('_token'))) {
            $em->remove($summary);
            $em->flush();
        }

        return $this->redirectToRoute('summary');
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

    /**
     * @Route(
     *     "summary/{id}/viewed/{job_id}",
     *     name="summary.view",
     *     methods={"GET"},
     *     requirements={"id" = "\d+", "job_id" = "\d+"}
     * )
     * @ParamConverter("job", options={"id" = "job_id"})
     *
     * @param Summary                $summary Summary entity
     * @param Job                    $job     Job entity
     * @param EntityManagerInterface $em      Entity manager
     *
     * @return Response
     */
    public function view(Summary $summary, Job $job, EntityManagerInterface $em): Response
    {
        if ($this->getUser()->getId() === $job->getCompany()->getUser()->getId()) {
            /** @var Application $application */
            $application = $em->getRepository(Application::class)->findOneBy(['job' => $job, 'summary' => $summary]);
            $application->setViewed(true);
            $em->flush();
        }

        return $this->redirectToRoute('summary.show', ['id' => $summary->getId()]);
    }
}