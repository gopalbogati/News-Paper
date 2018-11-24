<?php

namespace Bidhee\UserBundle\Twig\Extension;


use Doctrine\ORM\EntityManager;
use Bidhee\UserBundle\Entity\Author;
use Bidhee\UserBundle\Entity\User;

/**
 * Class UserExtension
 * @author Danepliz
 */
class UserExtension extends \Twig_Extension
{

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * UserExtension constructor.
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
                'author_select',
                [$this, 'authorSelect'],
                ['is_safe' => ['html']]
            )
        ];
    }

    public function authorSelect($name, $selected = null, $attr = '')
    {
        $authors = $this->em->getRepository(Author::class)->findAll();
        $html = '<select name="' . $name . '" ' . $attr . '>';
        $html .= '<option value="">-- Any Author --</option>';

        if (count($authors)) {
            foreach ($authors as $author) {
                $selectedString = ($selected == ($authorId = $author->getId())) ? 'selected="selected"' : '';
                $html .= '<option value="' . $authorId . '" ' . $selectedString . '> ' . $author->getName() . ' </option>';
            }

        }

        $html .= '</select>';

        return $html;
    }

    public function getName()
    {
        return 'bidhee_user_extension';
    }

}
