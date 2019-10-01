<?php

namespace App\Controller;

use App\Entity\Job;
use App\Repository\JobRepository;
use App\Entity\Category;
use App\Form\JobType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class JobController extends Controller
{
    /**
     * Lists all job entities.
     *
     * @Route("/", name="job.list")
     *
     * @return Response
     */
    public function list(EntityManagerInterface $em) : Response
    {
        // This will display everything
        //$jobs = $this->getDoctrine()->getRepository(Job::class)->findAll();

        $jobs = $em->getRepository(Job::class)->findActiveJobs();
        $categories = $em->getRepository(Category::class)->findWithActiveJobs();


        return $this->render('job/list.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * Finds and displays a job entity.
     *
     * @Route("job/{id}", name="job.show", methods="GET", requirements={"id" = "\d+"})
     *
     * @Entity("job", expr="repository.findActiveJob(id)")
     *
     * @param Job $job
     *
     * @return Response
     */
    public function show(Job $job) : Response
    {
        return $this->render('job/show.html.twig', [
            'job' => $job,
        ]);
    }

    /**
     * Creates a new job entity.
     *
     * @Route("/job/create", name="job.create",methods={"GET", "POST"})
     *
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $em, FileUploader $fileUploader)
    {
        $job = new Job();
        $form = $this->createForm(JobType::class,$job);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($job);
            $em->flush();

            return $this->redirectToRoute('job.list');
        }

        return $this->render('job/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
    * @Route("/job/{token}/edit", name="job.edit", methods={"GET", "POST"}, requirements={"token" = "\w+"})
     *
     * @param Request $request
     * @param Job $job
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function edit(Request $request, EntityManagerInterface $em, Job $job)
    {
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('job.list');
        }

        return $this->render('job/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
