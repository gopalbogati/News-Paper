<?php

namespace Bidhee\NewsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NewsMeta
 *
 * @ORM\Table(name="ng_news_meta")
 * @ORM\Entity(repositoryClass="Bidhee\NewsBundle\Repository\NewsMetaRepository")
 */
class NewsMeta
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
     * @ORM\Column(name="meta_description", type="text", nullable=true)
     */
    private $metaDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_keywords", type="string", length=255, nullable=true)
     */
    private $metaKeywords;

    /**
     * @var News
     *
     * @ORM\OneToOne(targetEntity="Bidhee\NewsBundle\Entity\News", inversedBy="meta")
     */
    private $news;


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
     * Set metaAuthor
     *
     * @param string $metaAuthor
     *
     * @return NewsMeta
     */
    public function setMetaAuthor($metaAuthor)
    {
        $this->metaAuthor = $metaAuthor;

        return $this;
    }

    /**
     * Get metaAuthor
     *
     * @return string
     */
    public function getMetaAuthor()
    {
        return $this->metaAuthor;
    }

    /**
     * Set metaDescription
     *
     * @param string $metaDescription
     *
     * @return NewsMeta
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * Get metaDescription
     *
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * Set metaKeywords
     *
     * @param string $metaKeywords
     *
     * @return NewsMeta
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;

        return $this;
    }

    /**
     * Get metaKeywords
     *
     * @return string
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     * @return News
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     * @param News $news
     *
     * @return NewsReview
     */
    public function setNews($news)
    {
        $this->news = $news;

        return $this;
    }
}

