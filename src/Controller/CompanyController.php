<?php


namespace App\Controller;


use App\Entity\Company;
use App\Entity\Job;
use App\Form\CompanyType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
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
     * Create a new company
     *
     * @Route("company/create", name="company.create", methods={"GET|POST"})
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
     * Finds and display company entity
     *
     * @Route(
     *     "/company/{id}",
     *     name="company.show",
     *     methods={"GET"},
     *     requirements={"id" = "\d+"})
     *
     * @param Request            $request   Http request
     * @param Company            $company   Company entity
     * @param PaginatorInterface $paginator Knp paginator
     *
     * @return Response
     */
    public function show(Request $request, Company $company, PaginatorInterface $paginator): Response
    {
        $activeJobs = $paginator->paginate(
            $this->getDoctrine()->getRepository(Job::class)->getPaginatedActiveJobsByCompanyQuery($company),
            $request->query->getInt('page', 1),
            $this->getParameter('max_per_page')
        );

        return $this->render('company/show.html.twig', [
            'company'    => $company,
            'activeJobs' => $activeJobs,
        ]);
    }
}