<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Proxies\__CG__\App\Entity\Category;

class CategoryService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function create(string $name)
    {
        $category = new Category();
        $category->setName($name);
        $this->em->persist($category);
        $this->em->flush();

        return $category;
    }
}