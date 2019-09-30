<?php

namespace App\Repository;

use DateTime;
use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{
    public function findWithActiveJobs()
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.jobs', 'j')
            ->where('j.expiresAt > :date')
            ->setParameter('date', new DateTime())
            ->getQuery()
            ->getResult();
    }
}