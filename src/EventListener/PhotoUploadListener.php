<?php
/**
 * Photo upload listener.
 */

namespace App\EventListener;

use App\Entity\Photo;
use App\Service\FileUploader;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class PhotoUploadListener
 *
 * @package App\EventListener
 */
class PhotoUploadListener
{
    private $uploader;

    /**
     * PhotoUploadListener constructor.
     *
     * @param FileUploader $uploader
     */
    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    /**
     * PrePersist hook.
     *
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    /**
     * PreUpdate hook.
     *
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    /**
     * Upload file.
     *
     * @param $entity
     */
    private function uploadFile($entity)
    {
        if (!$entity instanceof Photo) {
            return;
        }

        $file = $entity->getLocation();

        // only upload new files
        if ($file instanceof UploadedFile) {
            $fileName = $this->uploader->upload($file);
            $entity->setLocation($fileName);
        } elseif ($file instanceof File) {
            // prevents the full file path being saved on updates
            // as the path is set on the postLoad listener
            $entity->setLocation($file->getFilename());
        }
    }

    /**
     * PreRemove hook.
     *
     * @param LifecycleEventArgs $args
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->deleteFile($entity);
    }

    /**
     * Deletes file.
     *
     * @param $entity
     */
    private function deleteFile($entity)
    {
        if (!$entity instanceof Photo) {
            return;
        }

        $file = $entity->getLocation();

        $this->uploader->delete($file);
    }
}
