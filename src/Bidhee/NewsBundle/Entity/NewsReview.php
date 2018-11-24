<?php

namespace Bidhee\NewsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NewsReview
 *
 * @ORM\Table(name="ng_news_review")
 * @ORM\Entity(repositoryClass="Bidhee\NewsBundle\Repository\NewsReviewRepository")
 */
class NewsReview
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
     * @var int
     *
     * @ORM\Column(name="revision_count", type="integer", nullable=true)
     */
    private $revisionCount = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="share_count", type="integer", nullable=true)
     */
    private $shareCount = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="likes_count", type="integer", nullable=true)
     */
    private $likesCount = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="views_count", type="integer", nullable=true)
     */
    private $viewsCount = 0;

    /**
     * @var News
     *
     * @ORM\OneToOne(targetEntity="Bidhee\NewsBundle\Entity\News", inversedBy="review")
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
     * Set revisionCount
     *
     * @param integer $revisionCount
     *
     * @return NewsReview
     */
    public function setRevisionCount($revisionCount)
    {
        $this->revisionCount = $revisionCount;

        return $this;
    }

    /**
     * Get revisionCount
     *
     * @return int
     */
    public function getRevisionCount()
    {
        return $this->revisionCount;
    }

    /**
     * Set shareCount
     *
     * @param integer $shareCount
     *
     * @return NewsReview
     */
    public function setShareCount($shareCount)
    {
        $this->shareCount = $shareCount;

        return $this;
    }

    /**
     * Get shareCount
     *
     * @return int
     */
    public function getShareCount()
    {
        return $this->shareCount;
    }

    /**
     * Set likesCount
     *
     * @param integer $likesCount
     *
     * @return NewsReview
     */
    public function setLikesCount($likesCount)
    {
        $this->likesCount = $likesCount;

        return $this;
    }

    /**
     * Get likesCount
     *
     * @return int
     */
    public function getLikesCount()
    {
        return $this->likesCount;
    }

    /**
     * Set viewsCount
     *
     * @param integer $viewsCount
     *
     * @return NewsReview
     */
    public function setViewsCount($viewsCount)
    {
        $this->viewsCount = $viewsCount;

        return $this;
    }

    /**
     * Get viewsCount
     *
     * @return int
     */
    public function getViewsCount()
    {
        return $this->viewsCount;
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

