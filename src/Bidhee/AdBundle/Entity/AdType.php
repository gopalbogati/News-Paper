<?php

namespace Bidhee\AdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Bidhee\FoundationBundle\Traits\CreatedUpdateOnTrait;

/**
 * AdType
 *
 * @ORM\Table(name="ng_ad_type")
 * @ORM\Entity(repositoryClass="Bidhee\AdBundle\Repository\AdTypeRepository")
 */
class AdType
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="height", type="integer", length=6, nullable=true)
     */
    private $height;

    /**
     * @var integer
     *
     * @ORM\Column(name="width", type="integer", length=6, nullable=true)
     */
    private $width;

    /**
     * @var bool
     *
     * @ORM\Column(name="publish", type="boolean")
     */
    private $publish = false;

    use CreatedUpdateOnTrait;

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
     * @return AdType
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
     * Set publish
     *
     * @param boolean $publish
     *
     * @return AdType
     */
    public function setPublish($publish)
    {
        $this->publish = $publish;

        return $this;
    }

    /**
     * Get publish
     *
     * @return bool
     */
    public function getPublish()
    {
        return $this->publish;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * Get height
     *
     * @return mixed
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set Height
     *
     * @param mixed $height
     *
     * @return AdType
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get width
     *
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set width
     *
     * @param int $width
     *
     * @return AdType
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }


}

