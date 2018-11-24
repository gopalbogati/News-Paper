<?php

namespace Bidhee\FrontendBundle\Controller;

use Bidhee\NewsBundle\Entity\News;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NewsController extends Controller
{

    /**
     * Get Specific news
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
       /* $categoryId = $request->get('id');
        $category = $this->get('bidhee.category.service')->getCategory($categoryId);
        if (!$category) {
            return $this->render('BidheeFrontendBundle:Category:no-category.html.twig');
        }*/
        $categoryId = $request->get('id');
//        $category = $this->get('bidhee.category.service')->getCategory($categoryId);

        $newsId = $request->get('id');
        /** @var News $news */
        $news = $this->get('bidhee.news.service')->getSpecificNews($newsId);
        $latestNews = $this->get('bidhee.news.service')->getLatestNewsInnerPage(4, $newsId);
        $haveYouMissedNews = $this->get('bidhee.news.service')->getMissedNews(8);
        $relatedNews = $this->get('bidhee.news.service')->getRelatedNews($newsId);
        $albums = $this->get('bidhee.gallery.service')->getAlbums(1);
        $trending = $this->get('bidhee.news.service')->getViewCountsNews(7);
        $tajaUpdates = $this->get('bidhee.news.service')->newsWithIn24Hours();
        $getViewCountNews = $this->get('bidhee.news.service')->getViewCountsNews(7);
        $getAuthorsNews = $this->get('bidhee.news.service')->getAuthorsNews($offset = 1, $perPage = 3,
            $filters = []);

        $newsCategories = $news->getCategories();
        $relatedNewsByCategory = $this->get('bidhee.news.service')->getRelatedNewsToCategory(6, $newsCategories,
            $newsId);

        return $this->render('@BidheeFrontend/News/details.html.twig',
            compact('news', 'latestNews', 'relatedNews', 'haveYouMissedNews', 'author', 'authorNews', 'albums',
                'tajaUpdates','categoryId', 'getViewCountNews','relatedNewsByCategory', 'trending','getAuthorsNews'));

    }

    /**
     * @return Response
     */
    public function newsAction()
    {
        $latestNews = $this->get('bidhee.news.service')->getLatestNewsInnerPage(10);

        return $this->render('@BidheeFrontend/News/political-news.html.twig', compact('latestNews'));
    }

    public function searchNewsAction(Request $request)
    {
        $key = $request->get('key');
        //dd($key);
        $offset = $request->query->get('page', 1);
        $perPage = 5;
        $filters['key'] = $key;
        /* $relatedNews = $this->get('bidhee.news.service')->getRelatedNews($newsId);*/
        $albums = $this->get('bidhee.gallery.service')->getAlbums(1);
        $trending = $this->get('bidhee.news.service')->getViewCountsNews(7);
        $tajaUpdates = $this->get('bidhee.news.service')->newsWithIn24Hours();
        $getViewCountNews = $this->get('bidhee.news.service')->getViewCountsNews(7);

        $searchNews = $this->get('bidhee.news.service')->searchNews($offset, $perPage, $filters);

        $haveYouMissedNews = $this->get('bidhee.news.service')->getMissedNews(8);

        return $this->render('BidheeFrontendBundle:News:search.html.twig',
            compact('searchNews', 'haveYouMissedNews', 'getViewCountNews', 'tajaUpdates', 'trending', 'albums'));
    }

    public function newsByAuthorAction(Request $request)
    {
        $authorId = $request->get('author_id');

        $author = $this->get('bidhee.author.service')->getAuthor($authorId);

        if (!$author) {
            return $this->redirectToRoute('bidhee_frontend_homepage');
        }

        $offset = $request->query->get('page', 1);
        $perPage = 20;
        $filters['author_id'] = $authorId;

        $authorNews = $this->get('bidhee.news.service')->getAuthorsNews($offset, $perPage, $filters);
        $haveYouMissedNews = $this->get('bidhee.news.service')->getMissedNews(8);

        return $this->render('BidheeFrontendBundle:News:author.html.twig',
            compact('author', 'authorNews', 'haveYouMissedNews'));
    }


    /**
     * Submit news from prabash
     */
    public function submitYourNewsAction()
    {
        return $this->render('@BidheeFrontend/Pages/submit_your_news.html.twig');
    }

    /**
     * Check if News has published Status
     *
     * @param News $news
     * @return  bool
     */
    private function isValidNews(News $news)
    {
        if (!$news->getDeleted() && $news->getStatus() == News::NEWS_STATUS_PUBLISHED && $news->getPublished()) {
            return true;
        }

        return false;
    }

    /*public function searchNewsAction(Request $request)
    {
        $key = $request->get('key');

        //dd($key);

        $offset = $request->query->get('page', 1);
        $perPage = 5;
        $filters['key'] = $key;

        $searchNews = $this->get('bidhee.news.service')->searchNews($offset, $perPage, $filters);

        $haveYouMissedNews = $this->get('bidhee.news.service')->getMissedNews(8);

        return $this->render('BidheeFrontendBundle:News:search.html.twig',
            compact('searchNews', 'haveYouMissedNews'));
    }*/

    public function archiveAction(Request $request)
    {


        $filters['to'] = $request->get('to');
        $filters['from'] = $request->get('from');
        $filters['title'] = $request->get('title');
        $archiveNews = $this->get('bidhee.news.service')->getArchiveNews($filters);
        $data['archiveNews'] = $this->get('bidhee.pagination.service')->getPaginatedList($archiveNews, 8);
        $data['trending'] = $this->get('bidhee.news.service')->getViewCountsNews(7);
        $data['tajaUpdates'] = $this->get('bidhee.news.service')->newsWithIn24Hours();
        $data['albums'] = $this->get('bidhee.gallery.service')->getAlbums(1);

        return $this->render('@BidheeFrontend/News/archive.html.twig', $data);
    }
}
