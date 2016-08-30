<?php
namespace AppBundle\EventListener;

use AppBundle\Entity\Image;
use AppBundle\Entity\Type;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

/**
 * Created by PhpStorm.
 * User: Maps_red
 * Date: 29/08/2016
 * Time: 20:11
 */
class UploadListener
{
    /** * @var string $projectPath */
    private $projectPath;
    /** * @var string $typePath */
    private $typePath;

    /**
     * ProjectUploadListener constructor.
     * @param string $projectPath
     * @param string $typePath
     */
    public function __construct($projectPath, $typePath)
    {
        $this->projectPath = $projectPath;
        $this->typePath = $typePath;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $this->uploadFile($entity);
    }

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();
        $this->uploadFile($entity);
    }

    /**
     * @param $entity
     */
    private function uploadFile($entity)
    {
        // upload only works for Product entities
        /** @var UploadedFile $file */
        if ($entity instanceof Type && $entity->getFile() instanceof UploadedFile) {
            $file = $entity->getFile();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->typePath, $fileName);
            $entity->setFile($fileName);
        }elseif ($entity instanceof Image && $entity->getPath() instanceof UploadedFile) {
            $file = $entity->getPath();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->projectPath, $fileName);
            $entity->setPath($fileName);
        }else {
            return;
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof Type && is_string($entity->getFile())) {
            $entity->setFile(new File($this->typePath.'/'.$entity->getFile()));
        }elseif ($entity instanceof Image && is_string($entity->getPath())) {
            $entity->setPath(new File($this->projectPath.'/'.$entity->getPath()));
        }
    }
}