<?php


namespace App\Controller\Api\Job;


use App\Entity\Job;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JobController extends AbstractController
{
    /**
     * List all active jobs
     *
     * @Route(
     *    "/api/jobs",
     *     name="api.jobs",
     *     methods={"GET"},
     * )
     *
     * @param EntityManagerInterface $em Entity manager
     *
     * @return Response
     */
    public function getActiveJobs(EntityManagerInterface $em): Response
    {
        $jobs = $em->getRepository(Job::class)->findActiveJobs();

        return $this->json([
            'jobs' => array_map(static function ($job) {
                return [
                    'id'       => $job->getId(),
                    'city'     => $job->getLocation(),
                    'position' => $job->getPosition(),
                    'company'  => $job->getCompany(),
                    'category' => $job->getCategory()->getName()
                ];
            }, $jobs)
        ]);
    }
}
