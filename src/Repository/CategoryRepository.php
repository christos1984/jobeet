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
            ->andWhere('j.activated = :activated')
            ->setParameter('date', new DateTime())
            ->setParameter('activated', true)
            ->getQuery()
            ->getResult();
    }
}