<?php

namespace Bidhee\NewsBundle\Controller;

use Exception;
use Bidhee\NewsBundle\Entity\News;
use Bidhee\NewsBundle\Form\NewsType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NewsController extends Controller
{

    public function indexAction(Request $request)
    {
        $newsListQuery = $this->get('bidhee.news.service')->getNewsListQuery($request->query->all());

        $data['news'] = $this->get('bidhee.pagination.service')->getPaginatedList($newsListQuery);
        $data['pageTitle'] = 'News';
        $data['pageDescription'] = 'List';
        $data['possibleNewsStatus'] = News::$newsStatus;

        return $this->render('BidheeNewsBundle:News:index.html.twig', $data);
    }

    public function createAction(Request $request)
    {
        $data['pageDescription'] = 'Create';
        $news = null;
        $successMessage = 'News Added Successfully.';
        $newsUpdate = false;
        $images = [];

        $newsService = $this->get('bidhee.news.service');
        $oldStatus = '';

        if ($id = $request->get('id')) {
            $news = $newsService->getSpecificNews($id);

            if (!$news) {
                return $this->redirectToRoute('bidhee_admin_dashboard');
            }

            $newsUpdate = true;
            $oldStatus = $news->getStatus();
            $data['pageDescription'] = 'Update';
            $successMessage = 'News Updated Successfully.';

            $filePath = $this->container->getParameter("kernel.root_dir") . "/../web/uploads/news_images/";
            $cacheManager = $this->container->get('liip_imagine.cache.manager');
            foreach ($news->getImages() as $image) {
                $images[] = [
                    "id" => $image->getId(),
                    "name" => $image->getFileName(),
                    "size" => filesize($filePath . $image->getFileName()),
                    "thumbnail" => $cacheManager->getBrowserPath('uploads/news_images/' . $image->getFileName(),
                        'uploader_thumbnail')
                ];
            }
        }

        $form = $this->createForm(new NewsType(), $news, ['em' => $this->getDoctrine()]);

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $news = $form->getData();
                $em = $this->getDoctrine()->getManager();
                $images = $request->get('image');

                $categories = $news->getCategories();

                if( count($categories) > 0 ){
                    foreach($categories as $cat){
                        $news->removeCategory($cat);
                        $news->addCategory($cat);
                        if( $cat->getParent()){
                            $news->removeCategory($cat->getParent());
                            $news->addCategory($cat->getParent());
                        }
                    }
                }

                if ($images) {
                    foreach ($images as $image) {
                        $i = $em->find("BidheeNewsBundle:NewsImage", $image);

                        $news->addImage($i);
                        $i->setNews($news);
                        $em->persist($i);
                    }
                }

                $removedImages = $request->get('remove_image');
                if ($removedImages) {

                    foreach ($removedImages as $image) {
                        $i = $em->getRepository("BidheeNewsBundle:NewsImage")->findOneBy(['fileName' => $image]);
                        $news->removeImage($i);

                        $em->remove($i);
                    }
                }

                try {
                    $newsService->create($news);
                    $newsService->updateRelatedNews($news, $request->get('relatedNews'));
                    $newsService->updateReview($news, $newsUpdate);
                    $newsService->updateStatusLog($news, $oldStatus, $news->getStatus());
                    $this->addFlash('success', $successMessage);

                    return $this->redirectToRoute('bidhee_admin_news_list');
                } catch (Exception $e) {
                    $data['errorMessage'] = $e->getMessage();
                }
            }
        }

        $data['pageTitle'] = 'News';
        $data['form'] = $form->createView();
        $data['news'] = $news;
        $data['images'] = $images;

        return $this->render('BidheeNewsBundle:News:post.html.twig', $data);
    }

    public function trashAction(Request $request)
    {
        $id = $request->get('id');

        $em = $this->getDoctrine()->getManager();
        $newsRepo = $em->getRepository('BidheeNewsBundle:News');

        $news = $newsRepo->find($id);

        if (!$news) {
            $this->addFlash('error', 'News not found with id ' . $id);

            return $this->redirectToRoute('bidhee_admin_news_list');
        }

        $news->setStatus(News::NEWS_STATUS_TRASHED);

        $em->persist($news);

        try {
            $em->flush();
            $this->addFlash('success', 'News moved to trash successfully');
        } catch (Exception $e) {
            $this->addFlash('error', 'Unable to move News to trash');
        }

        return $this->redirectToRoute('bidhee_admin_news_list');
    }

    public function deleteAction(Request $request)
    {
        $id = $request->get('id');

        $em = $this->getDoctrine()->getManager();
        $newsRepo = $em->getRepository('BidheeNewsBundle:News');

        $news = $newsRepo->find($id);

        if (!$news) {
            $this->addFlash('error', 'News not found with id ' . $id);

            return $this->redirectToRoute('bidhee_admin_news_list');
        }

        $em->remove($news);

        try {
            $em->flush();
            $this->addFlash('success', 'News permanently deleted successfully');
        } catch (Exception $e) {
            $this->addFlash('error', 'Unable to delete News permanently');
        }

        return $this->redirectToRoute('bidhee_admin_news_list');
    }
}
