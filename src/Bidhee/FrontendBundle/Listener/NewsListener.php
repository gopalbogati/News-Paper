<?php

namespace Bidhee\FrontendBundle\Listener;

use Bidhee\FrontendBundle\Event\NewsEvent;
use Bidhee\NewsBundle\Entity\News;
use Doctrine\ORM\EntityManager;

class NewsListener
{

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * NewsListener constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {

        $this->em = $em;
    }

    /**
     * @param NewsEvent $event
     */
    public function onNewsRead(NewsEvent $event)
    {
        /** @var News $news */
        $news = $event->getNews();
        $news->getReview()->incrementViewCount();
        $this->em->persist($news);
        $this->em->flush();
    }
}
