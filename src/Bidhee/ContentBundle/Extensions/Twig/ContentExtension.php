<?php

/**
 * @Author Bhaktaraz Bhatta <bhattabhakta@gmail.com>
 */

namespace Bidhee\ContentBundle\Extensions\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

class ContentExtension extends \Twig_Extension
{

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('getFooterContents', [$this, 'getFooterContents']),
        ];
    }

    public function getFooterContents()
    {
        $em = $this->container->get('doctrine')->getManager();
        $contentRepo = $em->getRepository('BidheeContentBundle:Content');
        $contents = $contentRepo->findBy(['publish' => true, 'showOnFooterMenu' => true],['sortOrder'=>'asc']);

        return $contents;
    }

    public function getName()
    {
        return 'content_extension';
    }
}
