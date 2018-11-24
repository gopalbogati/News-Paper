<?php

namespace Bidhee\FrontendBundle\Extensions\Twig;

use Bidhee\NewsBundle\Entity\News;
use Bidhee\UserBundle\Entity\Author;
use Twig_Extension;
use Twig_SimpleFilter;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig_SimpleFunction;

/**
 * Class LlipImagineImageExtension
 * @author Deepak Pandey
 */
class LlipImagineImageExtension extends Twig_Extension
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
    public function getFilters()
    {
        return [
            new Twig_SimpleFilter('relative_path', [$this, 'relativePath']),
            new Twig_SimpleFilter('authorName', [$this, 'authorName'])
        ];
    }

    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('getAuthorImage', [$this, 'getAuthorImage']),
            new Twig_SimpleFunction('formatAuthorImage', [$this, 'formatAuthorImage'])
        ];
    }

    /**
     * @param $imageUrl
     * @return string
     */
    public function relativePath($imageUrl)
    {
        $request = $this->container->get('request');
        $baseUrl = $request->getSchemeAndHttpHost();
        $relativePath = str_replace($baseUrl, '', $imageUrl);

        return $relativePath;
    }

    /**
     * @param $newsId
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    public function getAuthorImage($newsId)
    {
        /** @var News $news */
        $news = $this->container->get('bidhee.news.service')->getSpecificNews($newsId);
        /** @var Author $author */
        $author = $news->getAuthor();
        if ($author instanceof Author && !is_null($author->getImageName())) {
            return '/uploads/author/' . $author->getImageName();
        }

        return '/uploads/author/default-author.png';
    }

    /**
     * @param $imageName
     * @return string
     */
    public function formatAuthorImage($imageName)
    {
        if (empty($imageName)) {
            return '/uploads/author/default-author.png';
        }

        return '/uploads/author/' . $imageName;
    }

    public function authorName($newsId)
    {
        /** @var News $news */
        $news = $this->container->get('bidhee.news.service')->getSpecificNews($newsId);
        /** @var Author $author */
        $author = $news->getAuthor();
        if ($author instanceof Author && !is_null($author->getName())) {
            return $author->getName();
        }

        return false;
    }

    /**
     * @return string
     */
    public function getName()
    {

        return 'relative_path';
    }
}
