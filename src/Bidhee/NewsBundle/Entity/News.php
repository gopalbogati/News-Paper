<?php

namespace Bidhee\NewsBundle\Entity;

use Bidhee\CategoryBundle\Entity\Category;
use Bidhee\FoundationBundle\Traits\CreatedUpdateOnTrait;
use Bidhee\UserBundle\Entity\User;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * News
 *
 * @ORM\Table(name="ng_news")
 * @ORM\Entity(repositoryClass="Bidhee\NewsBundle\Repository\NewsRepository")
 * @ExclusionPolicy("all")
 *
 */
class News
{

    const NEWS_STATUS_DRAFT = 'DRAFT';

    const NEWS_STATUS_PUBLISHED = 'PUBLISHED';

    const NEWS_STATUS_UNPUBLISHED = 'UNPUBLISHED';

    const NEWS_STATUS_ARCHIVED = 'ARCHIVED';

    const NEWS_STATUS_TRASHED = 'TRASHED';

    public static $newsStatus = [
        self::NEWS_STATUS_DRAFT => 'Draft',
        self::NEWS_STATUS_PUBLISHED => 'Published',
        self::NEWS_STATUS_UNPUBLISHED => 'Unpublished',
        self::NEWS_STATUS_ARCHIVED => 'Archived',
        self::NEWS_STATUS_TRASHED => 'Trashed',
    ];

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="text")
     * @Expose
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="subtitle", type="text",nullable=true)
     * @Expose
     */
    private $subtitle;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"title"}, updatable=false)
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @var string
     * @ORM\Column(name="news_slug", type="string", length=255, nullable=true)
     */
    private $newsSlug;

    /**
     *
     * @ORM\ManyToMany(targetEntity="Bidhee\CategoryBundle\Entity\Category")
     * @ORM\JoinTable(
     *     name="ng_news_categories",
     *     joinColumns={@ORM\JoinColumn(name="news_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")}
     * )
     */
    private $categories;

    /**
     * @ORM\ManyToMany(targetEntity="Bidhee\NewsBundle\Entity\News")
     * @ORM\JoinTable(
     *      name="ng_news_related_news",
     *     joinColumns={@ORM\JoinColumn(name="news_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="related_news_id", referencedColumnName="id")}
     * )
     */
    private $relatedNews;

    /**
     * @var string
     *
     * @ORM\Column(name="intro_text", type="text", nullable=false)
     * @Expose
     */
    private $introText;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     * @Expose
     */
    private $content;


    /**
     * @var string
     *
     * @ORM\Column(name="status", length=50, nullable=false)
     */
    private $status = self::NEWS_STATUS_DRAFT;


    /**
     * @var bool
     *
     * @ORM\Column(name="is_breaking_news", type="boolean")
     */
    private $isBreakingNews = false;

    /**
     * @var Author
     *
     * @ORM\ManyToOne(targetEntity="Bidhee\UserBundle\Entity\Author")
     * @Expose
     */
    private $author;


    /**
     * @var boolean
     *
     * @ORM\Column(name="is_author", type="boolean", nullable=false, options={"default" = 0})
     */
    private $authorcheck;



    use CreatedUpdateOnTrait;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Bidhee\UserBundle\Entity\User")
     */
    private $createdBy;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Bidhee\UserBundle\Entity\User")
     */
    private $updatedBy;

    /**
     * @var bool
     *
     * @ORM\Column(name="deleted", type="boolean")h
     */
    private $deleted = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="published", type="boolean")
     */
    private $published = true;

    /**
     * @var NewsMeta
     *
     * @ORM\OneToOne(targetEntity="Bidhee\NewsBundle\Entity\NewsMeta", mappedBy="news", cascade={"persist","remove"})
     */
    private $meta;

    /**
     * @var NewsReview
     *
     * @ORM\OneToOne(targetEntity="Bidhee\NewsBundle\Entity\NewsReview", mappedBy="news", cascade={"persist","remove"})
     */
    private $review;

    /**
     * @var string
     *
     * @ORM\Column(name="featured_image", type="string", length=255, nullable=true)
     * @Expose
     */
    private $featuredImage;

    /**
     *
     * @ORM\OneToMany(targetEntity="Bidhee\NewsBundle\Entity\NewsStatusLog", mappedBy="news")
     */
    private $newsStatusLogs;

    /**
     * @var NewsImage[] | Collection
     * @ORM\OneToMany(targetEntity="NewsImage", mappedBy="news", cascade={"persist", "remove"})
     */
    private $images;


    /**
     * @var DateTime
     *
     * @ORM\Column(name="publish_on", type="datetime")
     * @Expose
     */
    private $publishOn;


    /**
     * @var boolean
     *
     * @ORM\Column(name="is_important_news", type="boolean")
     */
    private $isImportantNews = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_featured", type="boolean")
     */
    private $isFeaturedNews = false;


    /**
     * @var string
     *
     * @ORM\Column(name="caption", type="string", nullable=true)
     * @Expose
     */
    private $caption;

    /**
     * @var string
     *
     * @ORM\Column(name="video_link", type="string", nullable=true)
     * @Expose
     */
    private $videoLink;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->newsStatusLogs = new ArrayCollection();
        $this->relatedNews = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->title;
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
     * Set title
     *
     * @param string $title
     *
     * @return News
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return News
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }


    /**
     * @return string
     */
    public function getNewsSlug()
    {
        return $this->newsSlug;
    }

    /**
     * @param string $newsSlug
     *
     * @return News
     */
    public function setNewsSlug($newsSlug)
    {
        $this->newsSlug = $newsSlug;

        return $this;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return News
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }


    /**
     * @return mixed
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param Category $category
     *
     * @return News
     */
    public function addCategory(Category $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * @param Category $category
     *
     * @return $this
     */
    public function removeCategory(Category $category)
    {
        $this->categories->removeElement($category);

        return $this;
    }

    public function hasCategory(Category $category)
    {
        return isset($this->categories[$category->getId()]) ? true : false;
    }

    public function resetCategories()
    {
        if (count($this->categories)) {
            foreach ($this->categories as $cat) {
                $this->removeCategory($cat);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRelatedNews()
    {
        return $this->relatedNews;
    }

    /**
     * @param News $news
     *
     * @return News
     */
    public function addRelatedNews(News $news)
    {
        $this->relatedNews[] = $news;

        return $this;
    }

    /**
     * @param News $news
     *
     * @return $this
     */
    public function removeRelatedNews(News $news)
    {
        $this->relatedNews->removeElement($news);

        return $this;
    }

    /**
     * @return News
     */
    public function resetRelatedNews()
    {
        if (count($this->relatedNews)) {
            foreach ($this->relatedNews as $news) {
                $this->removeRelatedNews($news);
            }
        }

        return $this;
    }


    /**
     * Set author
     *
     * @param User $author
     *
     * @return News
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set deleted
     *
     * @param boolean $deleted
     *
     * @return News
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted
     *
     * @return bool
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return News
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return bool
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return News
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set createdBy
     *
     * @param User $createdBy
     *
     * @return News
     */
    public function setCreatedBy(User $createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get updatedBy
     *
     * @return User
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * Set updatedBy
     *
     * @param User $updatedBy
     *
     * @return News
     */
    public function setUpdatedBy(User $updatedBy)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsBreakingNews()
    {
        return $this->isBreakingNews;
    }

    /**
     * @param boolean $isBreakingNews
     *
     * @return News
     */
    public function setIsBreakingNews($isBreakingNews)
    {
        $this->isBreakingNews = $isBreakingNews;

        return $this;
    }

    /**
     * @return NewsReview
     */
    public function getReview()
    {
        return $this->review;
    }

    /**
     * @param NewsReview $review
     *
     * @return News
     */
    public function setReview(NewsReview $review)
    {
        $this->review = $review;

        $review->setNews($this);

        return $this;
    }

    /**
     * @return string
     */
    public function getFeaturedImage()
    {
        return $this->featuredImage;
    }

    /**
     * @param string $featuredImage
     *
     * @return News
     */
    public function setFeaturedImage($featuredImage)
    {
        $this->featuredImage = $featuredImage;

        return $this;
    }


    /**
     * @return mixed
     */
    public function getNewsStatusLogs()
    {
        return $this->newsStatusLogs;
    }

    /**
     * @param NewsStatusLog $newsStatusLogs
     *
     * @return News
     */
    public function addNewsStatusLog($newsStatusLogs)
    {
        $this->newsStatusLogs[] = $newsStatusLogs;

        return $this;
    }

    /**
     * @param NewsStatusLog $newsStatusLog
     *
     * @return News
     */
    public function removeNewsStatusLog(NewsStatusLog $newsStatusLog)
    {
        $this->newsStatusLogs->removeElement($newsStatusLog);

        return $this;
    }


    public function addImage(NewsImage $image)
    {
        $this->images->add($image);
        $image->setNews($this);

        return $this;
    }

    public function removeImage(NewsImage $image)
    {
        $this->images->removeElement($image);
    }

    /**
     * @return NewsImage[]|Collection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param AdImage[]|Collection $images
     */
    public function setImages($images)
    {
        $this->images = $images;
    }

    /**
     * @return string
     */
    public function getPublishOn()
    {
        if (!is_null($this->publishOn)) {
            return $this->publishOn->format('Y-m-d H:i:s');
        } else {
            return $this->publishOn;
        }
    }

    /**
     * @param string $publishOn
     * @return $this
     */
    public function setPublishOn($publishOn)
    {
        $this->publishOn = new DateTime($publishOn);

        return $this;
    }

    /**
     * @return string
     */
    public function getIntroText()
    {
        return $this->introText;
    }

    /**
     * @param string $introText
     * @return $this
     */
    public function setIntroText($introText)
    {
        $this->introText = $introText;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsImportantNews()
    {
        return $this->isImportantNews;
    }

    /**
     * @param mixed $isImportantNews
     */
    public function setIsImportantNews($isImportantNews)
    {
        $this->isImportantNews = $isImportantNews;
    }

    /**
     * @return string
     */
    public function getVideoLink()
    {
        return $this->videoLink;
    }

    /**
     * @param string $videoLink
     */
    public function setVideoLink($videoLink)
    {
        $this->videoLink = $videoLink;
    }

    /**
     * @return string
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * @param string $caption
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;
    }

    /**
     * @return boolean
     */
    public function isIsFeaturedNews()
    {
        return $this->isFeaturedNews;
    }

    /**
     * @param boolean $isFeaturedNews
     */
    public function setIsFeaturedNews($isFeaturedNews)
    {
        $this->isFeaturedNews = $isFeaturedNews;
    }

    /**
     * @return string
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }


    /**
     * @param string $subtitle
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;
    }

    /**
     * @return bool
     */
    public function getAuthorcheck()
    {
        return $this->authorcheck;
    }

    /**
     * @param bool $authorcheck
     * @return $this
     */
    public function setAuthorcheck($authorcheck)
    {
        $this->authorcheck = $authorcheck;

        return $this;
    }


}

