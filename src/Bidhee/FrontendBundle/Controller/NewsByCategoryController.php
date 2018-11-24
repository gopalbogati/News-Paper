<?php

namespace Bidhee\FrontendBundle\Controller;

use Bidhee\FrontendBundle\Misc\NewsCategoryAlias;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class NewsByCategoryController
 * @author Deepak Pandey
 */
class NewsByCategoryController extends Controller
{

    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {

        $categoryId = $request->get('id');
        $category = $this->get('bidhee.category.service')->getCategory($categoryId);

        if (!$category) {
            return $this->render('BidheeFrontendBundle:Category:no-category.html.twig');
        }

        $offset = $request->query->get('page', 1);
        $perPage = 5;
        $filters['category_id'] = $category->getId();
        $categoryNews = $this->get('bidhee.category.service')->getCategoryNews($offset, $perPage, $filters);
        $categoryNews = $this->get('bidhee.pagination.service')->getPaginatedList($categoryNews, 8);
        $albums = $this->get('bidhee.gallery.service')->getAlbums(1);
        $trending=$this->get('bidhee.news.service')->getViewCountsNews(7);
        $tajaUpdates = $this->get('bidhee.news.service')->newsWithIn24Hours();
        $haveYouMissedNews = $this->get('bidhee.news.service')->getMissedNews(8);


        return $this->render('BidheeFrontendBundle:Category:category.html.twig',
            compact('categoryId', 'category', 'categoryNews', 'haveYouMissedNews', 'albums', 'tajaUpdates','trending',
                'categoryId'));
    }
}
