<?php
namespace Bidhee\NewsBundle\EventListener;

use Bidhee\NewsBundle\Entity\NewsImage;
use Doctrine\ORM\EntityManager;
use Oneup\UploaderBundle\Event\PostPersistEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class UploadListener
 * @package Bidhee\NewsBundle\EventListener
 *
 * @DI\Service("bidhee.imageupload.subscriber")
 * @DI\Tag("kernel.event_subscriber")
 */
class UploadListener implements EventSubscriberInterface
{

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @param EntityManager $em
     * @DI\InjectParams({
     *     "em" = @DI\Inject("doctrine.orm.default_entity_manager")
     * })
     */
    function __construct(EntityManager $em)
    {
        $this->em = $em;
    }


    public static function getSubscribedEvents()
    {
        return [
            "oneup_uploader.post_persist" => "onUpload"
        ];
    }

    public function onUpload(PostPersistEvent $event)
    {
        /** @var File $file */
        $file = $event->getFile();

        $response = $event->getResponse();

        $image = new NewsImage();
        $image->setFileName($file->getFilename());

        $this->em->persist($image);
        $this->em->flush();

        $response["image_id"] = $image->getId();
    }
} 
