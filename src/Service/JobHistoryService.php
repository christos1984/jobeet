<?php

namespace App\Service;

use App\Entity\Job;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class JobHistoryService
{
    private const MAX = 3;

    public function __construct(SessionInterface $session, EntityManagerInterface $em)
    {
        $this->session = $session;
        $this->em = $em;
    }


    public function addJob(Job $job)
    {
        $jobs = $this->getJobIds();

        // Add job id to the beginning of the array
        array_unshift($jobs, $job->getId());

        // Remove duplication of ids
        $jobs = array_unique($jobs);

        // Get only first 3 elements
        $jobs = array_slice($jobs, 0, self::MAX);

        // Store IDs in session
        $this->session->set('job_history', $jobs);
    }
    public function getJobs() : array
    {
        $jobs = [];
        $jobRepository = $this->em->getRepository(Job::class);

        foreach ($this->getJobIds() as $jobId) {
            $jobs[] = $jobRepository->findActiveJob($jobId);
        }

        return array_filter($jobs);
    }

    /**
     * @return array
     */
    private function getJobIds() : array
    {
        return $this->session->get('job_history', []);
    }
}