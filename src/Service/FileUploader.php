<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDirectory;

    public function __construct(string $targetDirectory, LoggerInterface $logger)
    {
        $this->targetDirectory = $targetDirectory;
        $this->logger = $logger;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }

    public function upload(UploadedFile $file)
    {
        $this->logger->info('Entering upload');
        $fileName = \bin2hex(\random_bytes(10)) . '.' . $file->guessExtension();
        $file->move(
            $this->targetDirectory,
            $fileName
        );
        return $fileName;
    }
}