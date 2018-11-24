<?php

namespace Bidhee\FrontendBundle\Event;

use Bidhee\NewsBundle\Entity\News;
use Symfony\Component\EventDispatcher\Event;

class NewsEvent extends Event
{

    /**
     * @var News
     */
    private $news;

    /**
     * NewsEvent constructor.
     * @param News $news
     */
    public function __construct(News $news)
    {
        $this->news = $news;
    }

    /**
     * @return News
     */
    public function getNews()
    {
        return $this->news;
    }
}
