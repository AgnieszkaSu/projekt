<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class FileUploader
 *
 * @package App\Service
 */
class FileUploader
{
    private $targetDirectory;

    /**
     * FileUploader constructor.
     *
     * @param $targetDirectory
     */
    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * Stores file on server.
     *
     * @param UploadedFile $file
     *
     * @return string
     */
    public function upload(UploadedFile $file)
    {
        $fileName = sha1(uniqid()).'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    /**
     * Deletes file from server.
     *
     * @param string $fileName
     */
    public function delete(string $fileName)
    {
        if (substr($fileName, 0, 1) !== '/') {
            $fileName = $this->getTargetDirectory().'/'.$fileName;
        }
        try {
            unlink($fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file delete
        }
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}
