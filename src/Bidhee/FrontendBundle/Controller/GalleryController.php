<?php

namespace Bidhee\FrontendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GalleryController extends Controller
{

    /**
     * Get Specific Album Gallery
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {

        //$albumId = $request->get('id');
        /* $page = 'sukrabar-detail';*/

        $albums = $this->get('bidhee.gallery.service')->getAlbums(12);

//        if ($albumId) {
//            $images = $this->get('bidhee.gallery.service')->getImagesById($albumId);
//        }


        return $this->render('@BidheeFrontend/Model-watch/model_watch_list.html.twig', compact('albums'));

    }


    public function detailAction(Request $request)
    {
        $albumId = $request->get('id');
        $images = $this->get('bidhee.gallery.service')->getImagesById($albumId);

        return $this->render('@BidheeFrontend/gallery/image.html.twig', compact('images'));

    }

    public function modelListingAction(Request $request)
    {

        $albumId = $request->get('id');
        $albums = $this->get('bidhee.gallery.service')->getAlbums($albumId);

        $latestNews = $this->get('bidhee.news.service')->getLatestNewsInnerPage(5);
        $galleryRepo = $this->getDoctrine()->getRepository('BidheeGalleryBundle:Album');
        $filter = [];
        $filter['status'] = true;
        $albumListQuery = $galleryRepo->getModelsListQuery($filter);
        $albumPaginate = $this->get('bidhee.pagination.service')->getPaginatedList($albumListQuery);
        $images = $this->get('bidhee.gallery.service')->getImagesById($albumId);


        return $this->render('@BidheeFrontend/Model-watch/model_watch_list.html.twig',
            compact('albums', 'latestNews', 'images','albumPaginate'));

    }


    public function modelDetailAction(Request $request)
    {

        $albumId = $request->get('id');
        $Album = $this->get('bidhee.gallery.service')->getAlbums($albumId);

        $albums = $this->get('bidhee.gallery.service')->getAlbums(12);
        $latestNews = $this->get('bidhee.news.service')->getLatestNewsInnerPage(5);
        $haveYouMissedNews = $this->get('bidhee.news.service')->getMissedNews(8);

        $images = $this->get('bidhee.gallery.service')->getImagesById($albumId);

        return $this->render('@BidheeFrontend/Model-watch/models-details.html.twig',
            compact('latestNews', 'Modeldetailsnews', 'images', 'content', 'Album', 'haveYouMissedNews',
                'relatedModels', 'albums'));

    }

}
