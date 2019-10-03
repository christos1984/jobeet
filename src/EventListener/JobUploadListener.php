<?php

namespace App\EventListener;

use App\Entity\Job;
use App\Service\FileUploader;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;

class JobUploadListener
{
    private $uploader;

    public function __construct(FileUploader $uploader, LoggerInterface $logger)
    {
        $this->uploader = $uploader;
        $this->logger = $logger;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->logger->info('entering prepersist');
        $entity = $args->getEntity();
        if (!$entity instanceof Job) {
            return;
        }
        $this->uploadFile($entity);
    }

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof Job) {
            return;
        }
        $this->logger->info('entering update');
        $this->uploadFile($entity);
    }

    private function uploadFile($entity){
        $logoFile = $entity->getLogo();

        if ($logoFile instanceof UploadedFile) {
            $fileName = $this->uploader->upload($logoFile);

            $entity->setLogo($fileName);
        }
    }


    /**
     * @param LifecycleEventArgs $args
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->stringToFile($entity);
    }

    /**
     * @param $entity
     */
    private function stringToFile($entity)
    {
        if (!$entity instanceof Job) {
            return;
        }

        if ($fileName = $entity->getLogo()) {
            $entity->setLogo(new File($this->uploader->getTargetDirectory() . '/' . $fileName));
        }
    }

    /**
     * @param $entity
     */
    private function fileToString($entity)
    {
        if (!$entity instanceof Job) {
            return;
        }

        $logoFile = $entity->getLogo();

        if ($logoFile instanceof File) {
            $entity->setLogo($logoFile->getFilename());
        }
    }

}