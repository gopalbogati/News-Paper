<?php

namespace Bidhee\FrontendBundle\Controller {

    use Bidhee\FrontendBundle\Misc\NewsCategoryAlias;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\Response;


    class HomeController extends Controller
    {

        public function indexAction()
        {
            $data['albums'] = $this->get('bidhee.gallery.service')->getAlbums(5);

            $data['getimportantNews'] = $this->get('bidhee.news.service')->getImportantNews(4);

            $data['tajaUpdates'] = $this->get('bidhee.news.service')->newsWithIn24Hours();

            $data['getTrendingNews'] = $this->get('bidhee.news.service')->getTrendingNews(4);

            $data['getViewCountNews'] = $this->get('bidhee.news.service')->getViewCountsNews(4);

            $data['getAuthorsNews'] = $this->get('bidhee.news.service')->getAuthorsNews($offset = 1, $perPage = 3,
                $filters = []);

            /* $data['featuredNews'] = $this->get('bidhee.news.service')->getFeaturedNews(4);*/
      /*       $breakingNewsLimit=$this->get('settings_manager')->get('breaking_news_limit');*/
            $data['breakingNews'] = $this->get('bidhee.news.service')->getBreakingNews(3);
            $data['importantNews'] = $this->get('bidhee.news.service')->getImportantNews(7);

            $data['rajnitiNews'] = $this->get('bidhee.news.service')->getNewsByCategory(NewsCategoryAlias::NEWS_RAJNITI,
                7);
            $data['samajNews'] = $this->get('bidhee.news.service')->getNewsByCategory(NewsCategoryAlias::NEWS_SAMAJ,
                5);

            $data['kala'] = $this->get('bidhee.news.service')->getNewsByCategory(NewsCategoryAlias::NEWS_KALA,
                3);

            $data['sportsNews'] = $this->get('bidhee.news.service')->getNewsByCategory(NewsCategoryAlias::SPORTS_NEWS,
                4);
            $data['important'] = $this->get('bidhee.news.service')->getNewsByCategory(NewsCategoryAlias::IMPORTANT_NEWS,
                5);
            $data['blog'] = $this->get('bidhee.news.service')->getNewsByCategory(NewsCategoryAlias::BLOG_NEWS,
                6);
            $data['sahityaNews'] = $this->get('bidhee.news.service')->getNewsByCategory(NewsCategoryAlias::SAHITYA_NEWS,
                3);
            $data['modelNews'] = $this->get('bidhee.news.service')->getNewsByCategory(NewsCategoryAlias::MODEL_NEWS,
                8);
            $data['featuredNews'] = $this->get('bidhee.news.service')->getNewsByCategory(NewsCategoryAlias::FEATURES_NEWS,
                4);
            $data['bicharNews'] = $this->get('bidhee.news.service')->getNewsByCategory(NewsCategoryAlias::BICHAR_NEWS,
                4);

            $data['interviewNews'] = $this->get('bidhee.news.service')->getNewsByCategory(NewsCategoryAlias::INTERVIEW_NEWS,
                4);
            $data['arthaNews'] = $this->get('bidhee.news.service')->getNewsByCategory(NewsCategoryAlias::ARTHA_NEWS,
                7);

            $data['foreignNews'] = $this->get('bidhee.news.service')->getNewsByCategory(NewsCategoryAlias::FOREIGN_NEWS,
                7);

            $data['prawashaNews'] = $this->get('bidhee.news.service')->getNewsByCategory(NewsCategoryAlias::PRAWASHA_NEWS,
                7);
            $data['deshNews'] = $this->get('bidhee.news.service')->getNewsByCategory(NewsCategoryAlias::DESH_NEWS,
                7);

            $data['entertainmentNews'] = $this->get('bidhee.news.service')->getNewsByCategory(NewsCategoryAlias::ENTERTAINMENT_NEWS,
                4);

            $data['healthNews'] = $this->get('bidhee.news.service')->getNewsByCategory(NewsCategoryAlias::HEALTH_NEWS,
                3);

            $data['videoNews'] = $this->get('bidhee.news.service')->getNewsByCategory(NewsCategoryAlias::VIDEO_NEWS,
                17);

            $data['photogalleryNews'] = $this->get('bidhee.news.service')->getNewsByCategory(NewsCategoryAlias::PHOTOGALLERY_NEWS,
                6);

            $data['bisheshNews'] = $this->get('bidhee.news.service')->getNewsByCategory(NewsCategoryAlias::BISHESH_NEWS,
                3);

            $data['prawashaNews'] = $this->get('bidhee.news.service')->getNewsByCategory(NewsCategoryAlias::PRAWASHA_NEWS,
                4);

            $data['patrapatrikaNews'] = $this->get('bidhee.news.service')->getNewsByCategory(NewsCategoryAlias::NEWS_PATRAPATRIKA,
                11);
            $data['patrapatrikaImportantNews'] = $this->get('bidhee.news.service')->getNewsByImportantCategory(NewsCategoryAlias::NEWS_PATRAPATRIKA,
                1);
            $data['rochak'] = $this->get('bidhee.news.service')->getNewsByCategory(NewsCategoryAlias::NEWS_ROCHAK,
                4);
            $data['sahitya'] = $this->get('bidhee.news.service')->getNewsByCategory(NewsCategoryAlias::SAHITYA_NEWS,
                4);

            $data['isHomePage'] = true;

            return $this->render('BidheeFrontendBundle:Home:index.html.twig', $data);
        }
    }
}
