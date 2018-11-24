<?php

namespace Bidhee\NewsBundle\Repository;

use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\Query;
use Doctrine\ORM\EntityRepository;
use Bidhee\CategoryBundle\Entity\Category;
use Bidhee\NewsBundle\Entity\News;
use Bidhee\NewsBundle\Entity\NewsReview;
use Bidhee\UserBundle\Entity\Author;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Bidhee\FoundationBundle\Doctrine\ModelManager;


/**
 * Class NewsRepository
 * @author Danepliz
 */
class NewsRepository extends EntityRepository
{

    /**
     * @param array $filters
     * @return array
     */
    public function getNewsList($filters = [])
    {
        $query = $this->getNewsListQuery($filters);

        return $query->getResult();

    }

    /**
     * @param $offset
     * @param $perPage
     * @param $filters
     * @return Pagerfanta
     */
    public function getAll($offset, $perPage, $filters)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('n')
            ->from('BidheeNewsBundle:News', 'n')
            ->leftJoin('n.categories', 'c')
            ->leftJoin('n.author', 'a')
            ->where('n.deleted = 0');

        if (is_array($filters) && !empty($filters)) {
            if (array_key_exists('category_id', $filters) and $filters['category_id'] != '') {
                $qb->andWhere('c.id = :category')->setParameter('category', $filters['category_id']);
            }
//            if (array_key_exists('categories', $filters) && !empty($filters['categories'])) {
//                print_r($filters['categories']);
////                exit;
//                $qb->add('where', $qb->expr()->in('c.id', ':cat'))
//                    ->setParameter('cat', $filters['categories']);
//            }

            if (array_key_exists('featured_image', $filters) and $filters['featured_image'] == true) {
                $qb->andWhere('n.featuredImage IS NOT NULL');
            }

            if (array_key_exists('key', $filters) && $filters['key'] != '') {
                $qb->andWhere('n.title LIKE :title')
                    ->setParameter('title', '%' . $filters['key'] . '%');
                $qb->orWhere('n.introText LIKE :title')
                    ->setParameter('title', '%' . $filters['key'] . '%');
                $qb->orWhere('n.content LIKE :title')
                    ->setParameter('title', '%' . $filters['key'] . '%');
            }

            if (array_key_exists('author_id', $filters) and $filters['author_id'] != '') {
                $qb->andWhere('a.id = :author')->setParameter('author', $filters['author_id']);
            }
        }

        $qb->orderBy('n.publishOn', 'desc');

        $adapter = new DoctrineORMAdapter($qb);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage($perPage);
        $pagerfanta->setCurrentPage($offset);

        return $pagerfanta;
    }

    /**
     * @param array $filters
     * @return Query
     */
    public function getNewsListQuery($filters = [])
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('n')
            ->from(News::class, 'n')
            ->leftJoin('n.categories', 'c')
            ->where('n.deleted = 0');

        if (array_key_exists('newsIds', $filters) && !empty($filters['newsIds'])) {
            $qb->add('where', $qb->expr()->in('n.id', ':cat'))
                ->setParameter('cat', $filters['newsIds']);
        }

        if (array_key_exists('published', $filters) and $filters['published'] != '') {
            $qb->andWhere('n.published = :published')->setParameter('published', $filters['published']);
        }

        if (array_key_exists('title', $filters) and $filters['title'] != '') {
            $qb->andWhere('n.title LIKE :title')->setParameter('title', '%' . $filters['title'] . '%');
        }

        if (array_key_exists('content', $filters) and $filters['content'] != '') {
            $qb->andWhere('n.content LIKE :content')->setParameter('content', '%' . $filters['content'] . '%');
        }

        if (array_key_exists('category', $filters) and $filters['category'] != '') {
            $qb->andWhere('c.id = :category')->setParameter('category', $filters['category']);
        }

        if (array_key_exists('author', $filters) and $filters['author'] != '') {
            $qb->andWhere('IDENTITY(n.author) = :author')->setParameter('author', $filters['author']);
        }

        if (array_key_exists('status', $filters) and $filters['status'] != '') {
            $qb->andWhere('n.status = :status')->setParameter('status', $filters['status']);
        }

        $qb->orderBy('n.createdOn', 'DESC');

        return $qb->getQuery();


    }

    /**
     * @param News $news
     */
    public function create(News $news)
    {
        $this->_em->persist($news);
        $this->_em->flush();
    }

    /**
     * @param $text
     * @return array
     */
    public function searchRelatedNews($text)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select(['n.id', 'n.title'])
            ->from(News::class, 'n')
            ->where('n.deleted = 0')
            ->andWhere('n.title LIKE :title')
            ->setParameter('title', '%' . $text . '%');

        $qb->orderBy('n.createdOn', 'DESC');

        return $qb->getQuery()->getScalarResult();
    }
    /**
     * @param $text
     * @return array
     */
    public function searchTrendingNews($text)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select(['n.id', 'n.title'])
            ->from(News::class, 'n')
            ->where('n.deleted = 0')
            ->andWhere('n.title LIKE :title')
            ->setParameter('title', '%' . $text . '%');

        $qb->orderBy('n.createdOn', 'DESC');

        return $qb->getQuery()->getScalarResult();
    }
    /**
     * Find Breaking News
     * @param null $limit
     * @return array
     */
    public function findBreakingNews($limit = null)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('n.id', 'n.title', 'n.introText', 'n.publishOn', 'n.featuredImage', 'n.newsSlug',
            'n.videoLink','n.subtitle',
            'a.id as authorId', 'a.name as authorName')
            ->from(News::class, 'n')
            ->leftJoin('n.author', 'a')
            ->where($qb->expr()->andX(
                $qb->expr()->eq('n.isBreakingNews', ':isBreaking'),
                $qb->expr()->eq('n.published', ':published'),
                $qb->expr()->eq('n.deleted', ':deleted'),
                $qb->expr()->lte('n.publishOn', ':publishOn')

            ))
            ->setParameters([
                'isBreaking' => true,
                'published' => true,
                'deleted' => false,
                'publishOn' => Carbon::now(new DateTimeZone('Asia/Kathmandu'))
            ])
            ->orderBy('n.publishOn', 'DESC');

        if (!is_null($limit)) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Returns featured News
     * @param null $limit
     * @return array
     */
    public function findFeaturedNews($limit = null)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('n')
            ->from(News::class, 'n')
            ->where($qb->expr()->andX(
                $qb->expr()->eq('n.published', ':published'),
                $qb->expr()->eq('n.deleted', ':deleted'),
                $qb->expr()->lte('n.publishOn', ':publishOn')
            ))
            ->setParameters([
                'published' => true,
                'deleted' => false,
                'publishOn' => Carbon::now(new DateTimeZone('Asia/Kathmandu'))
            ])
            ->orderBy('n.createdOn', 'DESC');

        if (!is_null($limit)) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Return scheduled news
     *
     * @return array
     */
    public function getScheduledNews()
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('n')
            ->from(News::class, 'n')
            ->where('n.deleted = 0')
            ->andWhere('n.published = 0');

        // need to check wheather news is scheduled or not

        return $qb->getQuery()->getResult();
    }

    /**
     * @param Category $category
     * @param $limit
     * @return array
     */
    public function getNewsByCategory(Category $category, $limit)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('c.name', 'n.id', 'n.title', 'n.introText', 'n.content', 'n.featuredImage', 'n.publishOn',
            'n.videoLink', 'a.name as authorName','n.authorcheck')
            ->from(News::class, 'n')
            ->innerJoin('n.categories', 'c')
            ->leftJoin('n.author', 'a')
            ->where($qb->expr()->andX(
                $qb->expr()->eq('c.id', ':catId'),
                $qb->expr()->eq('n.published', ':published'),
                $qb->expr()->eq('n.deleted', ':deleted'),
//                $qb->expr()->eq('n.isBreakingNews', ':isBreaking'),
//                $qb->expr()->eq('n.isImportantNews', ':isImportantNews'),
                $qb->expr()->lte('n.publishOn', ':publishOn')
            ))
            ->setParameters([
                'catId' => $category,
                'published' => true,
                'deleted' => false,
//                'isBreaking' => false,
//                'isImportantNews' => false,
                'publishOn' => Carbon::now(new DateTimeZone('Asia/Kathmandu'))
            ])
            ->orderBy('n.publishOn', 'DESC')
            ->setMaxResults($limit);


        return $qb->getQuery()->getArrayResult();
    }

    /**
     * @param array $dateRange
     * @param int $limit
     * @param array $categories
     * @return array
     */
    public function getMissedNews(array $dateRange, $limit, $categories)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('n.id', 'n.title', 'n.introText', 'n.featuredImage')
            ->from(News::class, 'n')
            ->innerJoin('n.categories', 'c')
//            ->join(NewsReview::class, 'nr')
            ->where($qb->expr()->andX(
                $qb->expr()->eq('n.published', ':published'),
                $qb->expr()->eq('n.deleted', ':deleted'),
//                $qb->expr()->gte('n.publishOn', ':fromDate'),
//                $qb->expr()->lte('n.publishOn', ':toDate'),
//                $qb->expr()->lte('n.publishOn', ':publishOn'),
                $qb->expr()->notIn('c.id', ':catIds')
            ))
            ->setParameters([
                'published' => true,
                'deleted' => false,
//                'fromDate' => $dateRange['fromDate'],
//                'toDate' => $dateRange['toDate'],
//                'publishOn' => Carbon::now(new DateTimeZone('Asia/Kathmandu')),
                'catIds' => implode(',', $categories)
            ])
//            ->orderBy('nr.viewsCount', 'DESC')
            ->groupBy('n.id')
            ->setMaxResults($limit);

        return $qb->getQuery()->getArrayResult();

    }

    /**
     * Get Trending News for this week
     *
     * @param $limit
     * @return array
     */
    public function getTrendingThisWeek($limit)
    {
//        $qb = $this->_em->createQueryBuilder();
//
//        $qb->select('n.id', 'n.title', 'n.introText', 'n.featuredImage')
//            ->from(News::class, 'n')
//            ->innerJoin('n.categories', 'c')
//            ->join(NewsReview::class, 'nr')
//            ->where($qb->expr()->andX(
//                $qb->expr()->neq('c.id', ':catId'),
//                $qb->expr()->eq('n.published', ':published'),
//                $qb->expr()->eq('n.deleted', ':deleted'),
//                $qb->expr()->gte('n.publishOn', ':fromDate'),
//                $qb->expr()->lte('n.publishOn', ':toDate'),
//                $qb->expr()->lte('n.publishOn', ':publishOn')
//            ))
//            ->setParameters([
//                'catId' => 88,
//                'published' => true,
//                'deleted' => false,
//                'fromDate' => Carbon::now(new DateTimeZone('Asia/Kathmandu'))->subWeek(1),
//                'toDate' => Carbon::now(new DateTimeZone('Asia/Kathmandu')),
//                'publishOn' => Carbon::now(new DateTimeZone('Asia/Kathmandu'))
//            ])
//            ->orderBy('nr.viewsCount', 'DESC')
//            ->groupBy('n.id')
//            ->setMaxResults($limit);
//
//        return $qb->getQuery()->getArrayResult();
        return $this->getLatestNewsInnerPage($limit, 0);
    }

    /**
     * Get List of recommended News for this week
     *
     * @param integer $limit
     * @return array
     */
    public function getRecommendedNews($limit)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('n.id', 'n.title', 'n.introText', 'n.featuredImage')
            ->from(News::class, 'n')
            ->where($qb->expr()->andX(
                $qb->expr()->eq('n.published', ':published'),
                $qb->expr()->eq('n.deleted', ':deleted'),
                $qb->expr()->eq('n.isRecommended', ':isRecommended'),
                $qb->expr()->gte('n.publishOn', ':fromDate'),
                $qb->expr()->lte('n.publishOn', ':toDate'),
                $qb->expr()->lte('n.publishOn', ':publishOn')
            ))
            ->setParameters([
                'published' => true,
                'deleted' => false,
                'isRecommended' => true,
                'fromDate' => Carbon::now(new DateTimeZone('Asia/Kathmandu'))->subWeek(1),
                'toDate' => Carbon::now(new DateTimeZone('Asia/Kathmandu')),
                'publishOn' => Carbon::now(new DateTimeZone('Asia/Kathmandu'))
            ])
            ->orderBy('n.publishOn', 'DESC')
            ->setMaxResults($limit);

        return $qb->getQuery()->getArrayResult();
    }

    /**
     * Fetch List of recent News to show in News inner page
     *
     * @param integer $limit
     * @param $newsId
     * @return array
     */
    public function getLatestNewsInnerPage($limit, $newsId)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('n.id', 'n.title', 'n.introText', 'n.featuredImage', 'n.publishOn')
            ->from(News::class, 'n')
            ->innerJoin('n.categories', 'c')
            ->where($qb->expr()->andX(
                $qb->expr()->eq('n.published', ':published'),
                $qb->expr()->eq('n.deleted', ':deleted'),
                $qb->expr()->neq('c.id', ':categoryId'),

                $qb->expr()->neq('n.id', ':newsId')
            ))
            ->setParameters([
                'categoryId' => 88,
                'published' => true,
                'deleted' => false,
                'newsId' => $newsId
            ])
            ->groupBy('n.id')
            ->orderBy('n.publishOn', 'DESC')
            ->setMaxResults($limit);

        return $qb->getQuery()->getArrayResult();
    }

    /**
     * @param $offset
     * @param $perPage
     * @param $filters
     * @return Query
     */

    public function getAllNewsByCategory($offset, $perPage, $filters)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('c.id', 'n.id', 'n.title', 'n.introText', 'n.content', 'n.featuredImage', 'n.publishOn',
            'a.id as author_id', 'a.name as author_name','n.authorcheck', 'a.imageName as author_image')
            ->from(News::class, 'n')
            ->join('n.categories', 'c')
            ->leftJoin('n.author', 'a')
            ->where($qb->expr()->andX(
                $qb->expr()->eq('c.id', ':cat_id'),
                $qb->expr()->eq('n.published', ':published'),
                $qb->expr()->eq('n.deleted', ':deleted'),
                $qb->expr()->lte('n.publishOn', ':publishOn')
            ))
            ->setParameters([
                'cat_id' => $filters['category_id'],
                'published' => true,
                'deleted' => false,
                'publishOn' => Carbon::now(new DateTimeZone('Asia/Kathmandu'))
            ])
            ->orderBy('n.publishOn', 'DESC');

        return $qb->getQuery();
    }

    /**
     * @param $limit
     * @param $categories
     * @param $newsId
     * @return array
     */
    public function getRelatedNewsToCategory($limit, $categories, $newsId)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('n.id', 'n.title', 'n.introText', 'n.featuredImage', 'a.name as authorName', 'n.publishOn')
            ->from(News::class, 'n')
            ->join('n.categories', 'c')
            ->join('n.author', 'a')
            ->where($qb->expr()->andX(
                $qb->expr()->in('c.id', ':cat_id'),
                $qb->expr()->eq('n.published', ':published'),
                $qb->expr()->eq('n.deleted', ':deleted'),
                $qb->expr()->lte('n.publishOn', ':publishOn'),
                $qb->expr()->neq('n.id', ':newsId')
            ))
            ->setParameters([
                'cat_id' => implode(',', $categories),
                'published' => true,
                'deleted' => false,
                'publishOn' => Carbon::now(new DateTimeZone('Asia/Kathmandu')),
                'newsId' => $newsId
            ])
            ->setMaxResults($limit)
            ->groupBy('n.id')
            ->orderBy('n.publishOn', 'DESC');

        return $qb->getQuery()->getArrayResult();
    }

    /**
     * Get all the news with important flag
     *
     * @param $limit
     * @return array
     */
    public function getImportantNews($limit)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('n.id', 'n.title', 'n.introText', 'n.featuredImage', 'n.publishOn','n.authorcheck')
            ->from(News::class, 'n')
            ->where($qb->expr()->andX(
                $qb->expr()->eq('n.published', ':published'),
                $qb->expr()->eq('n.deleted', ':deleted'),
                $qb->expr()->eq('n.isImportantNews', ':isImportantNews'),
                $qb->expr()->lte('n.publishOn', ':publishOn')
            ))
            ->setParameters([
                'published' => true,
                'deleted' => false,
                'isImportantNews' => true,
                'publishOn' => Carbon::now(new DateTimeZone('Asia/Kathmandu'))
            ])
            ->orderBy('n.publishOn', 'DESC')
            ->setMaxResults($limit);

        return $qb->getQuery()->getArrayResult();
    }

    /**
     * Get Main news for specific category
     *
     * @param $categoryId
     * @return array
     */
    public function getCategoryMainNews($categoryId)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('c.id', 'n.id', 'n.title', 'n.introText', 'n.content', 'n.featuredImage', 'n.publishOn')
            ->from(News::class, 'n')
            ->join('n.categories', 'c')
            ->where($qb->expr()->andX(
                $qb->expr()->eq('c.id', ':catId'),
                $qb->expr()->eq('n.published', ':published'),
                $qb->expr()->eq('n.isBreakingNews', ':isBreaking'),
                $qb->expr()->eq('n.deleted', ':deleted'),
                $qb->expr()->lte('n.publishOn', ':publishOn')
            ))
            ->setParameters([
                'catId' => $categoryId,
                'published' => true,
                'isBreaking' => false,
                'deleted' => false,
                'publishOn' => Carbon::now(new DateTimeZone('Asia/Kathmandu'))
            ])
            ->orderBy('n.publishOn', 'DESC')
            ->setMaxResults(1);

        return $qb->getQuery()->getArrayResult();
    }

    /**
     * Get featured news for specific category
     *
     * @param $categoryId
     * @return array
     */
    public function getCategoryFeaturedNews($categoryId)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('c.id', 'n.id', 'n.title', 'n.introText', 'n.content', 'n.featuredImage', 'n.publishOn',
            'n.categoryFeaturedImage')
            ->from(News::class, 'n')
            ->join('n.categories', 'c')
            ->where($qb->expr()->andX(
                $qb->expr()->eq('c.id', ':catId'),
                $qb->expr()->eq('n.published', ':published'),
                $qb->expr()->eq('n.isBreakingNews', ':isBreaking'),
                $qb->expr()->eq('n.deleted', ':deleted'),
                $qb->expr()->lte('n.publishOn', ':publishOn')
            ))
            ->setParameters([
                'catId' => $categoryId,
                'published' => true,
                'isBreaking' => false,
                'deleted' => false,
                'publishOn' => Carbon::now(new DateTimeZone('Asia/Kathmandu'))
            ])
            ->orderBy('n.publishOn', 'DESC')
            ->setMaxResults(1);

        return $qb->getQuery()->getArrayResult();
    }

    /**
     * @param integer $categoryId
     * @param $limit
     * @return array
     */
    public function getOtherNewsFromCategory($categoryId, $limit, $newsId)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('c.id', 'n.id', 'n.title', 'n.introText', 'n.content', 'n.featuredImage', 'n.publishOn')
            ->from(News::class, 'n')
            ->join('n.categories', 'c')
            ->where($qb->expr()->andX(
                $qb->expr()->eq('c.id', ':cat_id'),
                $qb->expr()->eq('n.published', ':published'),
                $qb->expr()->eq('n.deleted', ':deleted'),
                $qb->expr()->lte('n.publishOn', ':publishOn'),
                $qb->expr()->neq('n.id', ':newsId')
            ))
            ->setParameters([
                'cat_id' => $categoryId,
                'published' => true,
                'deleted' => false,
                'newsId' => $newsId,
                'publishOn' => Carbon::now(new DateTimeZone('Asia/Kathmandu')),
            ])
            ->setMaxResults($limit)
            ->groupBy('n.id')
            ->orderBy('n.publishOn', 'DESC');

        return $qb->getQuery()->getArrayResult();
    }

    /**
     * @return array
     * @param $limit
     */
    public function newsWithIn24Hours()
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('n.id', 'a.name as authorName', 'n.title', 'n.introText', 'n.content', 'n.caption', 'n.videoLink',
            'n.featuredImage',
            'n.publishOn')
            ->from(News::class, 'n')
            ->innerJoin('n.categories', 'c')
            ->leftJoin('n.author', 'a')
            ->where($qb->expr()->andX(
                $qb->expr()->eq('n.published', ':published'),
                $qb->expr()->eq('n.deleted', ':deleted'),
                $qb->expr()->neq('c.id', ':categoryId'),
                $qb->expr()->gte('n.publishOn', ':fromDate'),
                $qb->expr()->lte('n.publishOn', ':toDate')
            ))
            ->setParameters([
                'published' => true,
                'deleted' => false,
                'categoryId' => 88, //Cartoon Category
                'fromDate' => Carbon::now(new DateTimeZone('Asia/Kathmandu'))->subHour(24),
                'toDate' => Carbon::now(new DateTimeZone('Asia/Kathmandu')),
            ])
            ->groupBy('n.id')
            ->orderBy('n.publishOn', 'DESC');

        return $qb->getQuery()->getArrayResult();
    }

    /**
     * @param $limit
     * @param $categories
     * @return array
     */
    public function newsByInterestedCategories($limit, $categories)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('n.id', 'n.title', 'n.introText', 'n.featuredImage', 'n.publishOn')
            ->from(News::class, 'n')
            ->join('n.categories', 'c')
            ->where($qb->expr()->andX(
                $qb->expr()->in('c.id', ':cat_id'),
                $qb->expr()->eq('n.published', ':published'),
                $qb->expr()->eq('n.deleted', ':deleted'),
//                $qb->expr()->gte('n.publishOn', ':fromDate'),
                $qb->expr()->lte('n.publishOn', ':toDate')
            ))
            ->setParameters([
                'cat_id' => implode(',', $categories),
                'published' => true,
                'deleted' => false,
//                'fromDate' => Carbon::now(new DateTimeZone('Asia/Kathmandu'))->subHour(500),
                'toDate' => Carbon::now(new DateTimeZone('Asia/Kathmandu')),
            ])
            ->setMaxResults($limit)
            ->groupBy('n.id')
            ->orderBy('n.publishOn', 'DESC');

        return $qb->getQuery()->getArrayResult();
    }

    /**
     * @param Category $category
     * @param $limit
     * @return array
     */
    public function getNewsWithAuthorInfo(Category $category, $limit)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('c.name as categoryName', 'c.id as categoryId', 'n.id', 'n.title', 'n.introText', 'n.featuredImage',
            'n.publishOn', 'a.id as authorId',
            'a.name as authorName', 'a.imageName as authorImage')
            ->from(News::class, 'n')
            ->innerJoin('n.categories', 'c')
            ->join('n.author', 'a')
            ->where($qb->expr()->andX(
                $qb->expr()->eq('c.id', ':catId'),
                $qb->expr()->eq('n.published', ':published'),
                $qb->expr()->eq('n.deleted', ':deleted'),
                $qb->expr()->eq('n.isBreakingNews', ':isBreaking'),
                $qb->expr()->eq('n.isImportantNews', ':isImportantNews'),
                $qb->expr()->lte('n.publishOn', ':publishOn')
            ))
            ->setParameters([
                'catId' => $category,
                'published' => true,
                'deleted' => false,
                'isBreaking' => false,
                'isImportantNews' => false,
                'publishOn' => Carbon::now(new DateTimeZone('Asia/Kathmandu'))
            ])
            ->orderBy('n.publishOn', 'DESC')
            ->setMaxResults($limit);

        return $qb->getQuery()->getArrayResult();
    }

    /**
     * @param Category $category
     * @param $limit
     * @return array
     */
    public function getNewsByImportantCategory(Category $category, $limit)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('c.name', 'n.id', 'n.title', 'n.slug', 'n.introText', 'n.content', 'n.featuredImage', 'n.publishOn',
            'n.videoLink', 'n.isImportantNews')
            ->from(News::class, 'n')
            ->innerJoin('n.categories', 'c')
            ->where($qb->expr()->andX(
                $qb->expr()->eq('c.id', ':catId'),
                $qb->expr()->eq('n.published', ':published'),
                $qb->expr()->eq('n.deleted', ':deleted'),
                $qb->expr()->eq('n.isBreakingNews', ':isBreaking'),
                $qb->expr()->eq('n.isImportantNews', ':isImportantNews'),
                $qb->expr()->lte('n.publishOn', ':publishOn')
            ))
            ->setParameters([
                'catId' => $category,
                'published' => true,
                'deleted' => false,
                'isBreaking' => false,
                'isImportantNews' => true,
                'publishOn' => Carbon::now(new DateTimeZone('Asia/Kathmandu'))
            ])
            ->orderBy('n.publishOn', 'DESC')
            ->setMaxResults($limit);

        return $qb->getQuery()->getArrayResult();
    }

    /**
     * Get all the news with view flag
     *
     * @param $limit
     * @return array
     */
    public function getViewCountNews($limit)
    {
        $connection = $this->_em->getConnection();
        $statement = $connection->prepare("SELECT 
          nn.id,
          nn.title,
          nn.intro_text,
          nn.featured_image,
          nn.video_link
        FROM
          `ng_news_review` nr 
          JOIN 
            (SELECT 
              n.id,
              n.`title`,
              n.`intro_text`,
              n.`featured_image`,
              n.video_link,
              n.publish_on
            FROM
              ng_news n 
            WHERE n.publish_on >= :fromDate 
                AND n.publish_on <= :toDate 
              AND n.published = 1 
              AND n.deleted = 0 
             ) nn 
            ON nn.id = nr.id 
        GROUP BY nn.id 
        ORDER BY nr.`views_count` DESC 
        LIMIT " . $limit);

        $statement->bindValue('fromDate', Carbon::now(new DateTimeZone('Asia/Kathmandu'))->subWeek(1));
        $statement->bindValue('toDate', Carbon::now(new DateTimeZone('Asia/Kathmandu')));
        $statement->execute();

        return $statement->fetchAll();

    }


    /**
     * @param $filters
     * @return array
     */
    public function getArchiveNews($filters)
    {

        $qb = $this->_em->createQueryBuilder();
        $qb->select('n.id', 'n.title', 'n.introText', 'n.featuredImage', 'n.publishOn')
            ->from(News::class, 'n');
        if (array_key_exists('title', $filters) and $filters['title'] != '') {
            $qb->andWhere('n.title LIKE :title')
                ->setParameter('title', '%' . $filters['title'] . '%');
        }

        if (array_key_exists('from', $filters) and $filters['to'] != '') {
            $qb->add('where', $qb->expr()->between(
                'n.publishOn',
                ':from',
                ':to'
            ))->setParameters(['from' => $filters['from'], 'to' => $filters['to']]);
        }


        $qb->groupBy('n.id')
            ->orderBy('n.publishOn', 'DESC');

        return $qb->getQuery();
    }


}
