<?php

namespace Bidhee\CategoryBundle\Service;

use Doctrine\ORM\Query;
use Doctrine\ORM\EntityManager;
use Bidhee\CategoryBundle\Entity\Category;
use Bidhee\NewsBundle\Entity\News;

/**
 * Class CategoryService
 * @author Deepak Pandey
 */
class CategoryService
{

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * CategoryService constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return array
     */
    public function getCategories()
    {
        $data['pageTitle'] = 'Category';
        $data['pageDescription'] = 'List';
        $data['categories'] = $this->em->getRepository(Category::class)->findAll();

        return $data;
    }

    /**
     * @param array $filters
     * @return Query
     */
    public function getCategoriesListQuery($filters = [])
    {
        return $this->em->getRepository(Category::class)->getCategoriesListQuery($filters);
    }

    /**
     * @param int $id
     * @return Category|null|object
     */
    public function getCategory(int $id)
    {
        return $this->em->getRepository(Category::class)->find($id);

    }

    /**
     * @param string $alias
     * @return Category|null|object
     */
    public function getCategoryByAlias(string $alias)
    {
        return $this->em->getRepository(Category::class)->findOneBy(['alias' => $alias]);

    }


    /**
     * @param Category $category
     */
    public function create(Category $category)
    {
        return $this->em->getRepository(Category::class)->create($category);
    }

    /**
     * Get List of Menu Items
     * @return array
     */
    public function getMenuItems()
    {
        return $this->em->getRepository(Category::class)->getTopMenuItems();
    }

    /**
     * @param $offset
     * @param $perpage
     * @param $filters
     * @return array
     * @internal param Category $category
     */
    public function getCategoryNews($offset, $perpage, $filters)
    {
        return $this->em->getRepository(News::class)->getAllNewsByCategory($offset, $perpage, $filters);
    }
}
