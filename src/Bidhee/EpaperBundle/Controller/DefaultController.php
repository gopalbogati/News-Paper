<?php

namespace Bidhee\EpaperBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('EpaperBundle:Default:index.html.twig');
    }
}
