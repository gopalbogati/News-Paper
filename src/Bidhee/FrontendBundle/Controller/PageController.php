<?php

namespace Bidhee\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PageController extends Controller
{

    public function recentNewsAction()
    {
        $newsWithIn24Hours = $this->get('bidhee.news.service')->newsWithIn24Hours();

        return $this->render('@BidheeFrontend/Pages/bharkharai.html.twig', compact('newsWithIn24Hours'));
    }
}
