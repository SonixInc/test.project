<?php


namespace App\Controller;


use App\Entity\Category;
use App\Entity\Job;
use App\Service\JobHistoryService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * Finds and displays a category entity.
     *
     * @Route(
     *     "/category/{slug}/{page}",
     *     name="category.show",
     *     methods="GET",
     *     defaults={"page": 1},
     *     requirements={"page" = "\d+"}
     * )
     *
     * @param Category           $category          Category entity
     * @param PaginatorInterface $paginator         Knp paginator
     * @param int                $page              Page number
     * @param JobHistoryService  $jobHistoryService Job history
     *
     * @return Response
     */
    public function show(
        Category $category,
        PaginatorInterface $paginator,
        int $page,
        JobHistoryService $jobHistoryService
    ): Response
    {
        $activeJobs = $paginator->paginate(
            $this->getDoctrine()->getRepository(Job::class)->getPaginatedActiveJobsByCategoryQuery($category),
            $page,
            $this->getParameter('max_jobs_on_category')
        );

        return $this->render('category/show.html.twig', [
            'category'    => $category,
            'activeJobs'  => $activeJobs,
            'historyJobs' => $jobHistoryService->getJobs(),
        ]);
    }
}