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
        dd($job->getId());
    }

    public function getJobs()
    {

    }
}