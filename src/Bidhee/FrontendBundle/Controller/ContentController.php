<?php

namespace Bidhee\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ContentController extends Controller
{

    public function detailAction(Request $request)
    {
        $slug = $request->get('slug');

        $em = $this->getDoctrine()->getManager();
        $contentRepo = $em->getRepository('BidheeContentBundle:Content');
        $content = $contentRepo->findOneBy(['slug' => $slug, 'publish' => true]);

        return $this->render('@BidheeFrontend/Content/detail.html.twig', compact('content'));
    }

}
