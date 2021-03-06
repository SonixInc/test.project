<?php


namespace App\Controller;


use App\Entity\Application;
use App\Entity\Category;
use App\Entity\Company;
use App\Entity\Job;
use App\Entity\Summary;
use App\Entity\User;
use App\Form\JobType;
use App\Form\RespondFormType;
use App\Service\JobHistoryService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("job")
 */
class JobController extends AbstractController
{
    /**
     * Lists all job entities.
     *
     * @Route("/", name="job.list", methods="GET")
     *
     * @param EntityManagerInterface $em                Entity manager
     * @param JobHistoryService      $jobHistoryService Job history service
     *
     * @return Response
     */
    public function list(EntityManagerInterface $em, JobHistoryService $jobHistoryService): Response
    {
        $categories = $em->getRepository(Category::class)->findWithActiveJobs();

        return $this->render('job/list.html.twig', [
            'categories'  => $categories,
            'historyJobs' => $jobHistoryService->getJobs(),
        ]);
    }

    /**
     * Creates a new job entity.
     *
     * @Route("/create", name="job.create", methods={"GET", "POST"})
     * @IsGranted("ROLE_COMPANY")
     *
     * @param Request                $request Http request
     * @param EntityManagerInterface $em      Entity manager
     *
     * @return RedirectResponse|Response
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $job = new Job();

        $form = $this->createForm(JobType::class, $job, [
            'companies' => $em->getRepository(Company::class)->findActiveCompaniesForUser($user)
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($job);
            $em->flush();

            return $this->redirectToRoute(
                'job.preview',
                ['token' => $job->getToken()]
            );
        }

        return $this->render('job/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit existing job entity
     *
     * @Route("/{token}/edit", name="job.edit", methods={"GET", "POST"}, requirements={"token" = "\w+"})
     * @IsGranted("ROLE_COMPANY")
     *
     * @param Request                $request Http request
     * @param Job                    $job     Job entity
     * @param EntityManagerInterface $em      Entity manager
     *
     * @return Response
     */
    public function edit(Request $request, Job $job, EntityManagerInterface $em): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(JobType::class, $job, [
            'companies' => $em->getRepository(Company::class)->findActiveCompaniesForUser($user)
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute(
                'job.preview',
                ['token' => $job->getToken()]
            );
        }

        return $this->render('job/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Delete a job entity.
     *
     * @Route("/{token}/delete", name="job.delete", methods="DELETE", requirements={"token" = "\w+"})
     * @IsGranted("ROLE_COMPANY")
     *
     * @param Request                $request Http request
     * @param Job                    $job     Job entity
     * @param EntityManagerInterface $em      Entity manager
     *
     * @return Response
     */
    public function delete(Request $request, Job $job, EntityManagerInterface $em): Response
    {
        $form = $this->createDeleteForm($job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->remove($job);
            $em->flush();
        }

        return $this->redirectToRoute('job.list');
    }

    /**
     * Publish a job entity.
     *
     * @Route("/{token}/publish", name="job.publish", methods="POST", requirements={"token" = "\w+"})
     * @IsGranted("ROLE_COMPANY")
     *
     * @param Request                $request Http request
     * @param Job                    $job     Job entity
     * @param EntityManagerInterface $em      Entity manager
     *
     * @return Response
     */
    public function publish(Request $request, Job $job, EntityManagerInterface $em): Response
    {
        $form = $this->createPublishForm($job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $job->setActivated(true);

            $em->flush();

            $this->addFlash('notice', 'Your job was published');
        }

        return $this->redirectToRoute('job.preview', [
            'token' => $job->getToken(),
        ]);
    }


    /**
     * Finds and displays a job entity.
     *
     * @Route("/{id}", name="job.show", requirements={"id" = "\d+"})
     * @Entity("job", expr="repository.findActiveJob(id)")
     *
     * @param Job               $job               Job entity
     * @param JobHistoryService $jobHistoryService Job history
     *
     * @return Response
     */
    public function show(Job $job, JobHistoryService $jobHistoryService): Response
    {
        $jobHistoryService->addJob($job);

        return $this->render('job/show.html.twig', [
            'job' => $job,
        ]);
    }

    /**
     * @Route("/{id}/respond", name="job.respond", methods={"GET|POST"}, requirements={"id" = "\d+"})
     * @Entity("job", expr="repository.findActiveJob(id)")
     * @IsGranted("ROLE_WORKER")
     *
     * @param Request                $request Http request
     * @param Job                    $job     Job entity
     * @param EntityManagerInterface $em      Entity manager
     *
     * @return Response
     */
    public function respond(Request $request, Job $job, EntityManagerInterface $em): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $application = new Application();

        $form = $this->createForm(RespondFormType::class, $application, [
            'user_summaries' => $em->getRepository(Summary::class)->findBy(['user' => $user->getId()])
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $application->setJob($job);

            $em->persist($application);
            $em->flush();

            return $this->redirectToRoute('job.show', ['id' => $job->getId()]);
        }

        return $this->render('job/respond.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Finds and displays the preview page for a job entity.
     *
     * @Route("/{token}", name="job.preview", methods="GET", requirements={"token" = "\w+"})
     *
     * @param Job $job Job entity
     *
     * @return Response
     */
    public function preview(Job $job): Response
    {
        $deleteForm = $this->createDeleteForm($job);
        $publishForm = $this->createPublishForm($job);

        return $this->render('job/show.html.twig', [
            'job'              => $job,
            'hasControlAccess' => true,
            'deleteForm'       => $deleteForm->createView(),
            'publishForm'      => $publishForm->createView(),
        ]);
    }

    /**
     * Creates a form to delete a job entity.
     *
     * @param Job $job Job entity
     *
     * @return FormInterface
     */
    private function createDeleteForm(Job $job): FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('job.delete', ['token' => $job->getToken()]))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Creates a form to publish a job entity.
     *
     * @param Job $job Job entity
     *
     * @return FormInterface
     */
    private function createPublishForm(Job $job): FormInterface
    {
        return $this->createFormBuilder(['token' => $job->getToken()])
            ->setAction($this->generateUrl('job.publish', ['token' => $job->getToken()]))
            ->setMethod('POST')
            ->getForm();
    }
}
