<?php

namespace Bidhee\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('BidheeAdminBundle:Default:index.html.twig');
    }
}
