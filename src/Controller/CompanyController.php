<?php


namespace App\Controller;


use App\Entity\Company;
use App\Entity\Feedback;
use App\Entity\Job;
use App\Entity\User;
use App\Form\CompanyType;
use App\Form\FeedbackType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CompanyController
 *
 * @package App\Controller
 */
class CompanyController extends AbstractController
{
    /**
     * List of active companies
     *
     * @Route("company", name="company", methods={"GET"})
     *
     * @return Response
     */
    public function index(): Response
    {
        $companies = $this->getDoctrine()->getRepository(Company::class)->findAll();

        return $this->render('company/index.html.twig', [
            'companies' => $companies,
        ]);
    }

    /**
     * List of auth user companies
     *
     * @Route("company/own", name="company.own", methods={"GET"})
     *
     * @param Request            $request   Http request
     * @param PaginatorInterface $paginator Knp paginator
     *
     * @return Response
     */
    public function own(Request $request, PaginatorInterface $paginator): Response
    {
        /** @var Company[] $companies */
        $companies = $paginator->paginate(
            $this->getDoctrine()->getRepository(Company::class)->findBy(['user' => $this->getUser()]),
            $request->query->getInt('page', 1),
            $this->getParameter('max_per_page')
        );

        return $this->render('company/index.html.twig', [
            'companies' => $companies
        ]);
    }

    /**
     * Create a new company
     *
     * @Route("company/create", name="company.create", methods={"GET|POST"})
     * @IsGranted("ROLE_USER")
     *
     * @param Request                $request      Http request
     * @param EntityManagerInterface $em           Entity Manager
     * @param FileUploader           $fileUploader File uploader
     *
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $em, FileUploader $fileUploader): Response
    {
        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile|null $logoFile */
            $logoFile = $form->get('logo')->getData();

            if ($logoFile instanceof UploadedFile) {
                $fileName = $fileUploader->upload($logoFile);

                $company->setLogo($fileName);
            }

            $em->persist($company);
            $em->flush();

            return $this->redirectToRoute('company.show', ['id' => $company->getId()]);
        }

        return $this->render('company/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Edit company
     *
     * @Route("company/{id}/edit", name="company.edit", methods={"GET|POST"}, requirements={"id" = "\d+"})
     *
     * @param Request                $request Http request
     * @param Company                $company Company entity
     * @param EntityManagerInterface $em      Entity manager
     *
     * @return Response
     */
    public function edit(Request $request, Company $company, EntityManagerInterface $em): Response
    {
        if ($company->getUser() !== $this->getUser()) {
            $this->addFlash('error', 'Access denied.');
            return $this->redirectToRoute('company.show', ['id' => $company->getId()]);
        }

        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('company.show', ['id' => $company->getId()]);
        }

        return $this->render('company/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Delete company
     *
     * @Route("/company/{id}/delete", name="company.delete", methods={"DELETE"}, requirements={"id" = "\d+"})
     *
     * @param Request                $request Http request
     * @param Company                $company Company entity
     * @param EntityManagerInterface $em      Entity manager
     *
     * @return Response
     */
    public function delete(Request $request, Company $company, EntityManagerInterface $em): Response
    {
        if ($company->getUser() !== $this->getUser()) {
            $this->addFlash('error', 'Access denied.');
            return $this->redirectToRoute('company.show', ['id' => $company->getId()]);
        }

        if ($this->isCsrfTokenValid('delete' . $company->getId(), $request->request->get('_token'))) {
            $em->remove($company);
            $em->flush();
        }

        return $this->redirectToRoute('company');
    }

    /**
     * Finds and display company entity
     *
     * @Route(
     *     "/company/{id}",
     *     name="company.show",
     *     methods={"GET|POST"},
     *     requirements={"id" = "\d+"})
     *
     * @param Request                $request   Http request
     * @param Company                $company   Company entity
     * @param PaginatorInterface     $paginator Knp paginator
     * @param EntityManagerInterface $em        Entity manager
     *
     * @return Response
     */
    public function show(Request $request, Company $company, PaginatorInterface $paginator, EntityManagerInterface $em): Response
    {
        $feedback = new Feedback();

        $form = $this->createForm(FeedbackType::class, $feedback);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $feedback->setUser($this->getUser());
            $feedback->setCompany($company);
            $em->persist($feedback);
            $em->flush();
            return $this->redirectToRoute('company.show', ['id' => $company->getId()]);
        }


        $activeJobs = $paginator->paginate(
            $this->getDoctrine()->getRepository(Job::class)->getPaginatedActiveJobsByCompanyQuery($company),
            $request->query->getInt('page', 1),
            $this->getParameter('max_per_page')
        );

        return $this->render('company/show.html.twig', [
            'form'       => $form->createView(),
            'company'    => $company,
            'activeJobs' => $activeJobs,
        ]);
    }

}
