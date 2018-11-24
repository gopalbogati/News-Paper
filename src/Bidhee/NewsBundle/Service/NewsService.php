<?php

namespace Bidhee\NewsBundle\Service;

use Carbon\Carbon;
use DateTimeZone;
use Doctrine\ORM\EntityManager;
use Bidhee\NewsBundle\Entity\News;
use Bidhee\NewsBundle\Entity\NewsReview;
use Bidhee\CategoryBundle\Entity\Category;
use Bidhee\NewsBundle\Entity\NewsStatusLog;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class NewsService
 * @author Danepliz
 */
class NewsService
{

    private $categories = [88];

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var TokenStorageInterface
     */
    private $token;

    /**
     * NewsService constructor.
     * @param EntityManager $entityManager
     * @param TokenStorageInterface $token
     */
    public function __construct(EntityManager $entityManager, TokenStorageInterface $token)
    {
        $this->em = $entityManager;

        $this->token = $token;
    }

    public function getNewsListQuery($filters = [])
    {
        return $this->em->getRepository(News::class)->getNewsListQuery($filters);
    }

    /**
     * @param array $filters
     * @return mixed
     */
    public function getNews($filters = [])
    {
        $data['news'] = $this->em->getRepository(News::class)->getNewsList($filters);
        $data['pageTitle'] = 'News';
        $data['pageDescription'] = 'List';

        return $data;
    }

    /**
     * @param $id
     * @return News|null|object
     */
    public function getSpecificNews($id)
    {
        return $this->em->getRepository(News::class)->find($id);
    }

    /**
     * @param News $news
     * @return mixed
     */
    public function create(News $news)
    {
        return $this->em->getRepository(News::class)->create($news);
    }

    /**
     * @param News $news
     * @param bool $isUpdating
     */
    public function updateReview(News $news, $isUpdating = false)
    {
        $newsReview = $this->findNewsReview($news);

        if ($isUpdating) {
            $revisionCount = ($newsReview->getRevisionCount()) ? $newsReview->getRevisionCount() : 0;
            $newsReview->setRevisionCount($revisionCount + 1);
            $news->setUpdatedBy($this->token->getToken()->getUser());
        } else {
            $news->setCreatedBy($this->token->getToken()->getUser());
        }

        $this->em->persist($newsReview);
        $this->em->persist($news);
        $this->em->flush();
    }

    /**
     * @param News $news
     * @return NewsReview|null|object
     */
    public function findNewsReview(News $news)
    {
        $newsReview = $this->em->getRepository(NewsReview::class)->findOneBy([
            'news' => $news->getId()
        ]);

        if (!$newsReview) {
            $newsReview = new NewsReview();
            $newsReview->setNews($news);
        }

        return $newsReview;
    }

    /**
     * Create new record of NewsStatusLog if news is created
     * or when news status is changed
     *
     * @param News $news
     * @param $oldStatus
     * @param $newStatus
     */
    public function updateStatusLog(News $news, $oldStatus, $newStatus)
    {
        if ($oldStatus != $newStatus) {
            $newsStatusLog = new NewsStatusLog();
            $newsStatusLog->setNews($news)
                ->setStatus($newStatus)
                ->setUser($this->token->getToken()->getUser());
            $this->em->persist($newsStatusLog);
            $news->addNewsStatusLog($newsStatusLog);
            $this->em->persist($news);
            $this->em->flush();
        }
    }

    /**
     * Get List of Breaking news
     * @param null $limit
     * @return array
     */
    public function getBreakingNews($limit = null)
    {
        $breakingNews = $this->em->getRepository(News::class)->findBreakingNews($limit);

        return $breakingNews;
    }

    /**
     * Get Featured News
     * @param null $limit
     * @return mixed
     */
    public function getFeaturedNews($limit = null)
    {
        $featuredNews = $this->em->getRepository(News::class)->findFeaturedNews($limit);

        return $featuredNews;
    }

    /**
     * Increment Pageviews based on cookie
     * @param News $news
     */
    public function incrementViewsCount(News $news)
    {
        $newsReview = $this->findNewsReview($news);
        $viewCount = ($newsReview->getViewsCount()) ? $newsReview->getViewsCount() : 0;
        $newsReview->setViewsCount($viewCount + 1);
        $this->em->persist($newsReview);
        $this->em->flush();
    }

    /**
     * @param News $news
     * @param array $relatedNewsIds
     */
    public function updateRelatedNews(News $news, $relatedNewsIds)
    {
        $news->resetRelatedNews();
        if (count($relatedNewsIds)) {
            foreach ($relatedNewsIds as $id) {
                $relatedNews = $this->getSpecificNews($id);
                if ($relatedNews) {
                    $news->addRelatedNews($relatedNews);
                }
            }
        }

        $this->create($news);
    }

    /**
     * Get News by Category to show in landing page
     * @param string $categoryAlias
     * @param integer $limit
     * @return array
     */
    public function getNewsByCategory($categoryAlias, $limit)
    {
        $category = $this->em->getRepository(Category::class)->findOneBy(['alias' => $categoryAlias]);

        if ($category) {
            return $this->em->getRepository(News::class)->getNewsByCategory($category, $limit);
        }

        return [];
    }

    /**
     * Get News by search key
     * @param $offset
     * @param $perPage
     * @param $filters
     * @return \Pagerfanta\Pagerfanta
     */
    public function searchNews($offset, $perPage, $filters)
    {
        return $this->em->getRepository(News::class)->getAll($offset, $perPage, $filters);
    }

    /**
     * Get News by author
     * @param $offset
     * @param $perPage
     * @param $filters
     * @return \Pagerfanta\Pagerfanta
     */
    public function getAuthorsNews($offset, $perPage, $filters)
    {
        return $this->em->getRepository(News::class)->getAll($offset, $perPage, $filters);
    }

    /**
     * @param int $limit
     * @return array
     */
    public function getMissedNews($limit)
    {
        $dateRange = $this->getDateRange();

        return $this->em->getRepository(News::class)->getMissedNews($dateRange, $limit, $this->categories);
    }

    /**
     * Get Date range for last 3 week leaving running week for missing news
     * @return array
     */
    private function getDateRange()
    {
        return [
            'fromDate' => Carbon::now(new DateTimeZone('Asia/Kathmandu'))->subWeek(4),
            'toDate' => Carbon::now(new DateTimeZone('Asia/Kathmandu'))->subWeek(1)
        ];
    }

    /**
     * Returns array of trending news
     *
     * @param integer $limit
     * @return array
     */
    public function getTrendingNews($limit)
    {
        return $this->em->getRepository(News::class)->getTrendingThisWeek($limit);

    }

    /**
     * Returns array of recommended news
     *
     * @param integer $limit
     * @return array
     */
    public function getRecommendedNews($limit)
    {
        return $this->em->getRepository(News::class)->getRecommendedNews($limit);
    }

    /**
     * Returns array of latest news to show in inner page
     *
     * @param integer $limit
     * @param int $newsId
     * @return array
     */
    public function getLatestNewsInnerPage($limit, $newsId = 0)
    {
        return $this->em->getRepository(News::class)->getLatestNewsInnerPage($limit, $newsId);
    }

    /**
     * @param $limit
     * @param $newsCategories
     * @param $newsId
     * @return \Doctrine\ORM\Query
     */
    public function getRelatedNewsToCategory($limit, $newsCategories, $newsId)
    {
        $categories = [];
        if ($newsCategories) {
            /** @var Category $category */
            foreach ($newsCategories as $category) {
                $categories[] = $category->getId();
            }
        }

        return $this->em->getRepository(News::class)->getRelatedNewsToCategory($limit, $categories, $newsId);
    }

    /**
     * @param $limit
     * @return array
     */
    public function getImportantNews($limit)
    {
        return $this->em->getRepository(News::class)->getImportantNews($limit);
    }

    /**
     * Provides list of related News
     *
     * @param $newsId
     * @return array|null
     */
    public function getRelatedNews($newsId)
    {
        $news = $this->getSpecificNews($newsId);
        $relatedNews = $news->getRelatedNews();
        if (count($relatedNews) > 0) {
            return $relatedNews;
        }

        return null;
    }

    /**
     * @param $categoryId
     * @return array
     */
    public function getCategoryMainNews($categoryId)
    {
        return $this->em->getRepository(News::class)->getCategoryMainNews($categoryId);
    }

    /**
     * @param $categoryId
     * @return array
     */
    public function getCategoryFeaturedNews($categoryId)
    {
        return $this->em->getRepository(News::class)->getCategoryFeaturedNews($categoryId);
    }

    /**
     * @param $categoryId
     * @param $limit
     * @param $newsId
     * @return array
     */
    public function getOtherNewsFromCategory($categoryId, $limit, $newsId)
    {
        return $this->em->getRepository(News::class)->getOtherNewsFromCategory($categoryId, $limit, $newsId);
    }

    /**
     * Return News published within 24 hours
     * @param $limit
     * @return array
     */
    public function newsWithIn24Hours()
    {
        return $this->em->getRepository(News::class)->newsWithIn24Hours();
    }

    /**
     * @param $limit
     * @param $categories
     * @return array
     */
    public function newsByInterestedCategories($limit, $categories)
    {
        return $this->em->getRepository(News::class)->newsByInterestedCategories($limit, $categories);
    }

    /**
     * @param string $categoryAlias
     * @param int $limit
     * @return array
     */
    public function getNewsByCategoryWithAuthor($categoryAlias, $limit)
    {
        $category = $this->em->getRepository(Category::class)->findOneBy(['alias' => $categoryAlias]);
        if ($category) {
            return $this->em->getRepository(News::class)->getNewsWithAuthorInfo($category, $limit);
        }

        return [];
    }

    /**
     * Get News by Category to show in landing page
     * @param string $categoryAlias
     * @param integer $limit
     * @return array
     */
    public function getNewsByImportantCategory($categoryAlias, $limit)
    {
        $category = $this->em->getRepository(Category::class)->findOneBy(['alias' => $categoryAlias]);

        if ($category) {
            return $this->em->getRepository(News::class)->getNewsByImportantCategory($category, $limit);
        }

        return [];
    }

    /**
     * Returns Page view count news
     *
     * @param integer $limit
     * @return array
     */
    public function getViewCountsNews($limit)
    {
        return $this->em->getRepository(News::class)->getViewCountNews($limit);
    }


    /**
     * @param $filters
     * @return mixed
     */
    public function getArchiveNews($filters)
    {
        return $this->em->getRepository(News::class)->getArchiveNews($filters);
    }
}
