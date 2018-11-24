<?php

namespace Bidhee\FrontendBundle\Extensions\Twig;

use Twig_Extension;
use Twig_SimpleFunction;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RecommendedTrendingNewsExtension extends Twig_Extension
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
            new Twig_SimpleFunction('getRecommendedNews', [$this, 'getRecommendedNews']),
            new Twig_SimpleFunction('getTrendingNews', [$this, 'getTrendingNews']),
        ];
    }

    /**
     * Returns List of recommendedNews
     * @param integer $limit
     * @return array
     */
    public function getRecommendedNews($limit)
    {
        return $this->container->get('bidhee.news.service')->getRecommendedNews($limit);
    }

    /**
     * Returns list of trending news
     *
     * @param integer $limit
     * @return array
     */
    public function getTrendingNews($limit)
    {
        return $this->container->get('bidhee.news.service')->getTrendingNews($limit);
    }

    public function getName()
    {
        return 'recommended_trending_news_extension';
    }
}
