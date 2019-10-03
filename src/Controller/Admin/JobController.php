<?php

namespace App\Controller\Admin;

use App\Entity\Job;
use App\Form\JobType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class JobController extends AbstractController
{
    /**
     * @Route("/admin/jobs/{page}", name="admin.job.list", defaults={"page": 1})
     */
    public function list(EntityManagerInterface $em, PaginatorInterface $paginator, int $page){
        $jobs = $this->getDoctrine()->getRepository(Job::class)->findAll();

        $jobs1 = $paginator->paginate(
            $jobs,
            $page,
            $this->getParameter('max_per_page'),
            [
                PaginatorInterface::DEFAULT_SORT_FIELD_NAME => 'createdAt',
                PaginatorInterface::DEFAULT_SORT_DIRECTION => 'DESC',
            ]
        );
        return $this->render('admin/job/list.html.twig', [
            'jobs' => $jobs1,
        ]);
    }


    /**
     * @Route("/admin/job/create", name="admin.job.create", methods="GET|POST")
     */
    public function create(Request $request, EntityManagerInterface $em)
    {
        $job = new Job();
        $form = $this->createForm(JobType::class,$job);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em->persist($job);
            $em->flush();

            return $this->redirectToRoute('admin.job.list');
        }

        return $this->render('admin/job/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit job.
     *
     * @Route("/admin/job/{id}/edit", name="admin.job.edit", methods="GET|POST", requirements={"id" = "\d+"})
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param Job $job
     *
     * @return Response
     */
    public function edit(Request $request, EntityManagerInterface $em, Job $job) : Response
    {
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('admin.job.list');
        }

        return $this->render('admin/job/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

       /**
     * Delete job.
     *
     * @Route("/admin/job/{id}/delete", name="admin.job.delete", methods="DELETE", requirements={"id" = "\d+"})
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param Job $job
     *
     * @return Response
     */
    public function delete(Request $request, EntityManagerInterface $em, Job $job) : Response
    {
        if ($this->isCsrfTokenValid('delete' . $job->getId(), $request->request->get('_token'))) {
            $em->remove($job);
            $em->flush();
        }

        return $this->redirectToRoute('admin.job.list');
    }
}