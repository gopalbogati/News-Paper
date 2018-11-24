<?php

namespace Bidhee\CategoryBundle\Twig\Extension;


use Doctrine\ORM\EntityManager;
use Bidhee\CategoryBundle\Entity\Category;

/**
 * Class CategoryExtension
 * @author Danepliz
 */
class CategoryExtension extends \Twig_Extension
{

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * CategoryExtension constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction(
                'category_select',
                [$this, 'categorySelect'],
                ['is_safe' => ['html']]
            )
        ];
    }


    public function categorySelect($name, $selected = null, $attr = '')
    {
        $categories = $this->em->getRepository(Category::class)->getCategorySelectList();

        $html = '<select name="' . $name . '" ' . $attr . '>';
        $html .= '<option value="">-- Select Category --</option>';

        if (count($categories)) {   
            foreach ($categories as $category) {
                $selectedString = ($selected == ($catId = $category->getId())) ? 'selected="selected"' : '';
                $html .= '<option value="' . $catId . '" ' . $selectedString . '> ' . $category->getLabel() . ' </option>';
            }

        }

        $html .= '</select>';

        return $html;
    }

    public function getName()
    {
        return 'bidhee_category_extension';
    }

}
