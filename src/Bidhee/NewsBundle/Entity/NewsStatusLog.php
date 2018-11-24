<?php

namespace Bidhee\NewsBundle\Entity;

use Bidhee\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * News
 *
 * @ORM\Table(name="ng_news_status_log")
 * @ORM\Entity(repositoryClass="Bidhee\NewsBundle\Repository\NewsRepository")
 */
class NewsStatusLog
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
     * @var News
     *
     * @ORM\ManyToOne(targetEntity="Bidhee\NewsBundle\Entity\News", inversedBy="newsStatusLogs")
     */
    private $news;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=20, nullable=false)
     */
    private $status = News::NEWS_STATUS_DRAFT;

    /**
     * @var DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_date", type="datetime", nullable=false)
     */
    private $createdDate;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Bidhee\UserBundle\Entity\User")
     */
    private $user;


    /**
     * @return string
     */
    public function __toString()
    {
        return $this->status;
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
     * @return News
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     * @param News $news
     *
     * @return NewsStatusLog
     */
    public function setNews(News $news)
    {
        $this->news = $news;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return NewsStatusLog
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * @param DateTime $createdDate
     *
     * @return NewsStatusLog
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return NewsStatusLog
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

}