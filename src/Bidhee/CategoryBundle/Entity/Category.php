<?php

namespace Bidhee\CategoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Bidhee\FoundationBundle\Traits\CreatedUpdateOnTrait;

/**
 * Category
 *
 * @ORM\Table(name="ng_category")
 * @ORM\Entity(repositoryClass="Bidhee\CategoryBundle\Repository\CategoryRepository")
 */
class Category
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="alias", type="string", length=200, unique=true)
     */
    private $alias;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="Bidhee\CategoryBundle\Entity\Category", inversedBy="children")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Bidhee\CategoryBundle\Entity\Category", mappedBy="parent")
     */
    private $children;

    /**
     * @var integer
     *
     * @ORM\Column(name="ordering", type="integer", nullable=true)
     */
    private $ordering;

    /**
     * @var boolean
     *
     * @ORM\Column(name="publish", type="boolean", nullable=false, options={"default" = 1})
     */
    private $publish;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_top_menu_item", type="boolean", nullable=false, options={"default" = 0})
     */
    private $isTopMenuItem;


    /**
     * @var boolean
     *
     * @ORM\Column(name="trash", type="boolean", nullable=false, options={"default" = 0})
     */
    private $trash;

    /**
     * @ORM\ManyToMany(targetEntity="Bidhee\NewsBundle\Entity\News", mappedBy="categories")
     */
    private $news;

    use CreatedUpdateOnTrait;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->news = new ArrayCollection();
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set alias
     *
     * @param string $alias
     *
     * @return Category
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Category
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return boolean
     */
    public function isPublish()
    {
        return $this->publish;
    }

    /**
     * @return bool
     */
    public function getPublish()
    {
        return $this->publish;
    }

    /**
     * @param boolean $publish
     */
    public function setPublish($publish)
    {
        $this->publish = $publish;
    }

    /**
     * @return Category
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Category $parent
     *
     * @return Category
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param Category $category
     * @return Category
     */
    public function addChildren(Category $category)
    {
        $this->children[] = $category;

        return $this;
    }

    /**
     * @param Category $category
     * @return Category
     */
    public function removeChildren(Category $category)
    {
        $this->children->removeElement($category);

        return $this;
    }

    /**
     * @return Category
     */
    public function resetChildren()
    {
        $this->children = new ArrayCollection();

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsTopMenuItem()
    {
        return $this->isTopMenuItem;
    }

    /**
     * @param boolean $isTopMenuItem
     * @return $this
     */
    public function setIsTopMenuItem($isTopMenuItem)
    {
        $this->isTopMenuItem = $isTopMenuItem;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     * @return int
     */
    public function getOrdering()
    {
        return $this->ordering;
    }

    /**
     * @param int $ordering
     * @return $this
     */
    public function setOrdering($ordering)
    {
        $this->ordering = $ordering;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isTrash()
    {
        return $this->trash;
    }

    /**
     * @param boolean $trash
     * @return $this
     */
    public function setTrash($trash)
    {
        $this->trash = $trash;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    private $label;

    public function setLabel($label){
        $this->label = $label;
    }

    public function getLabel(){
        return $this->label ? $this->label : $this->name;
    }
}
