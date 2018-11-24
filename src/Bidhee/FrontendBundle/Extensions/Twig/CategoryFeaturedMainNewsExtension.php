<?php

namespace Bidhee\FrontendBundle\Extensions\Twig;

use Twig_Extension;
use Twig_SimpleFunction;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class RelatedNewsExtension
 * @author Deepak Pandey
 */
class CategoryFeaturedMainNewsExtension extends Twig_Extension
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
            new Twig_SimpleFunction('getCategoryMainNews', [$this, 'getCategoryMainNews']),
            new Twig_SimpleFunction('getCategoryFeaturedNews', [$this, 'getCategoryFeaturedNews'])
        ];
    }

    /**
     * Returns list of category main news
     *
     * @param $categoryId
     * @return array
     */
    public function getCategoryMainNews($categoryId)
    {
        return $this->container->get('bidhee.news.service')->getCategoryMainNews($categoryId);
    }


    /**
     * Returns List of category featured News
     *
     * @param $categoryId
     * @return array
     */
    public function getCategoryFeaturedNews($categoryId)
    {
        return $this->container->get('bidhee.news.service')->getCategoryFeaturedNews($categoryId);
    }

    public function getName()
    {
        return 'category_main_featured_news_extension';
    }
}
