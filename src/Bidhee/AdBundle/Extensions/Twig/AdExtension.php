<?php

/**
 * @Author Bhaktaraz Bhatta <bhattabhakta@gmail.com>
 */

namespace Bidhee\AdBundle\Extensions\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

class AdExtension extends \Twig_Extension
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
            new \Twig_SimpleFunction('getAds', [$this, 'getAds']),
        ];
    }

    public function getAds($placement, $limit = null)
    {
        $em = $this->container->get('doctrine')->getManager();
        $adRepo = $em->getRepository('BidheeAdBundle:Ad');
        $ads = $adRepo->getAds($placement, $limit);

        return $ads;
    }

    public function getName()
    {
        return 'ad_extension';
    }
}
