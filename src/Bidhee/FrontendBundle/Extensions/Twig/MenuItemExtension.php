<?php

namespace Bidhee\FrontendBundle\Extensions\Twig;

use Twig_Extension;
use Twig_SimpleFunction;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MenuItemExtension extends Twig_Extension
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
            new Twig_SimpleFunction('getMenuItems', [$this, 'getMenuItems'])
        ];
    }

    /**
     * Returns List of top menu items
     * @param integer $categoryId
     * @return array
     */
    public function getMenuItems($categoryId)
    {
        $topMenuItems = $this->container->get('bidhee.category.service')->getMenuItems();
        $topMenu = [];
        foreach ($topMenuItems as $menuItem) {
            $menuItem['status'] = '';
            if ($menuItem['id'] === $categoryId) {
                $menuItem['status'] = 'active';
            }
            $topMenu[] = $menuItem;
        }

        return $topMenu;
    }

    public function getName()
    {
        return 'menu_item_extension';
    }
}
