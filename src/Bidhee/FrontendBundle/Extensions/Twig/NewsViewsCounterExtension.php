<?php

namespace Bidhee\FrontendBundle\Extensions\Twig;


use Bidhee\NewsBundle\Entity\News;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig_Extension;
use Twig_SimpleFunction;

class NewsViewsCounterExtension extends Twig_Extension
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * NewsViewsCounterExtension constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('incrementNewsHitCounter', [$this, 'incrementNewsHitCounter']),
        ];
    }

    /**
     * @param News $news
     */
    public function incrementNewsHitCounter(News $news)
    {
        $this->container->get('bidhee.news.service')->incrementViewsCount($news);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'bidhee_view_counter';
    }
}
