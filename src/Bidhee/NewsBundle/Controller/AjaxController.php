<?php

namespace Bidhee\NewsBundle\Controller;

use Bidhee\NewsBundle\Entity\News;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AjaxController extends Controller
{

    public function toggleAction(Request $request)
    {
        $entityClass = $request->get('entity');
        $property = $request->get('property');
        $entityId = $request->get('id');
        $currentStatus = (boolean)$request->get('status');

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository($entityClass)->find($entityId);

        if (!$entity) {
            return new JsonResponse(['status' => $currentStatus], 400);
        }

        $getMethod = 'get' . ucfirst($property);
        $setMethod = 'set' . ucfirst($property);

        $entity->$setMethod(!$entity->$getMethod());

        $em->persist($entity);
        try {
            $em->flush();

            return new JsonResponse(['status' => $entity->$getMethod()]);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => $entity->$getMethod()], 400);
        }
    }

    public function searchRelatedNewsAction(Request $request)
    {
        $searchText = $request->get('search');
        $result = $this->getDoctrine()->getRepository(News::class)->searchRelatedNews($searchText);
        $response = [];

        if (count($result)) {
            foreach ($result as $r) {
                $response[] = [
                    'id' => $r['id'],
                    'title' => $r['title'],
                ];
            }
        }

        return new JsonResponse($response);
    }
    public function searchTrendingNewsAction(Request $request)
    {
        $searchText = $request->get('search');
        $result = $this->getDoctrine()->getRepository(News::class)->searchTrendingNews($searchText);
        $response = [];

        if (count($result)) {
            foreach ($result as $r) {
                $response[] = [
                    'id' => $r['id'],
                    'title' => $r['title'],
                ];
            }
        }

        return new JsonResponse($response);
    }
}
