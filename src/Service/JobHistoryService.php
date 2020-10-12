<?php


namespace App\Service;


use App\Entity\Job;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class JobHistoryService
{
    private const MAX = 3;

    private $session;
    private $em;

    public function __construct(SessionInterface $session, EntityManagerInterface $em)
    {
        $this->session = $session;
        $this->em = $em;
    }

    public function addJob(Job $job): void
    {
        $jobs = $this->getJobIds();

        array_unshift($jobs, $job->getId());

        $jobs = array_unique($jobs);

        $jobs = array_slice($jobs, 0, self::MAX);

        $this->session->set('job_history', $jobs);
    }

    public function getJobs(): array
    {
        $jobs = [];
        $jobRepository = $this->em->getRepository(Job::class);

        foreach ($this->getJobIds() as $jobId) {
            $jobs[] = $jobRepository->findActiveJob($jobId);
        }

        return array_filter($jobs);
    }

    private function getJobIds(): array
    {
        return $this->session->get('job_history', []);
    }
}