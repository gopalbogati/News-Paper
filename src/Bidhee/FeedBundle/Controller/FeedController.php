<?php

namespace Bidhee\FeedBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class FeedController
 * @author Deepak Pandey <er.pandeydip@gmail.com>
 */
class FeedController extends Controller
{

    public function indexAction()
    {
        $feed = $this->container->get('bidhee.feed.service')->getFeeds();

        return $this->render('@BidheeFeed/Feed/index.html.twig', compact('feed'));
    }
}
