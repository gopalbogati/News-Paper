<?php

namespace Bidhee\FrontendBundle\Extensions\Twig;

use Twig_Extension;
use Twig_SimpleFunction;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class RelatedNewsExtension
 * @author Deepak Pandey
 */
class RelatedNewsExtension extends Twig_Extension
{

    /**
     * @var ContainerInterface
     */
    private $container;

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
            new Twig_SimpleFunction('getRelatedNews', [$this, 'getRelatedNews']),
            new Twig_SimpleFunction('getOtherNewsFromCategory', [$this, 'getOtherNewsFromCategory'])
        ];
    }

    /**
     * Returns List of top menu items
     * @param integer $newsId
     * @return array
     */
    public function getRelatedNews($newsId)
    {
        return $this->container->get('bidhee.news.service')->getRelatedNews($newsId);
    }

    /**
     * @param integer $categoryId
     * @param $limit
     * @param $newsId
     * @return array
     */
    public function getOtherNewsFromCategory($categoryId, $limit, $newsId)
    {
        return $this->container->get('bidhee.news.service')->getOtherNewsFromCategory($categoryId, $limit, $newsId);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'related_news_extension';
    }
}
