<?php


namespace App\EventListener;


use App\Entity\Company;
use App\Service\FileUploader;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class CompanyUploadListener
 *
 * @package App\EventListener
 */
class CompanyUploadListener
{
    /**
     * @var FileUploader
     */
    private $uploader;

    /**
     * @param FileUploader $uploader
     */
    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    /**
     * @param $entity
     */
    private function uploadFile($entity): void
    {
        if (!$entity instanceof Company) {
            return;
        }

        $logoFile = $entity->getLogo();

        if ($logoFile instanceof UploadedFile) {
            $fileName = $this->uploader->upload($logoFile);

            $entity->setLogo($fileName);
        }
    }

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
        $this->fileToString($entity);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postLoad(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        $this->stringToFile($entity);
    }

    /**
     * @param $entity
     */
    private function stringToFile($entity): void
    {
        if (!$entity instanceof Company) {
            return;
        }

        if ($fileName = $entity->getLogo()) {
            $entity->setLogo(new File($this->uploader->getTargetDirectory() . '/' . $fileName));
        }
    }

    /**
     * @param $entity
     */
    private function fileToString($entity): void
    {
        if (!$entity instanceof Company) {
            return;
        }

        $logoFile = $entity->getLogo();

        if ($logoFile instanceof File) {
            $entity->setLogo($logoFile->getFilename());
        }
    }
}